<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        //echo "users_controller construct called<br><br>";
    } 

    public function index() {
        echo "This is the index page";
    }

    public function signup() {

        $this->template->content = View::instance('v_users_signup');
        $this->template->title = "Sign Up";

        # CSS/JS includes

        $client_files_head = Array(
            '/css/signup.css'
        );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        echo $this->template;

    }

    public function p_signup() {

        $_POST['created']   = Time::now();
        $_POST['modified']  = Time::now();

        // encrypt the password
        $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);

        // encrypt the token
        $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

        // this is how we actually get our data into the database
        $user_id = DB::instance(DB_NAME)->insert('users', $_POST);

        # CSS/JS includes

        $client_files_head = Array(
            '/css/signup.css'
        );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        $this->template->content = View::instance('v_users_p_signup');
        $this->template->title = "Signed Up";

        echo $this->template;

    }

    public function login($error = NULL) {

        // set up view
        $this->template->content = View::instance('v_users_login');

        // title
        $this->template->title = "Login";

        // pass error
        $this->template->content->error = $error;

        // CSS
        $client_files_head = Array(
            '/css/signup.css'
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // render template

        echo $this->template;
    }

    public function p_login() {

        // sanitize
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        // hash submitted password
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        // search db for email/password, return token if available
        $q = "SELECT token FROM users WHERE email = '".$_POST['email']."' AND password = '".$_POST['password']."'";

        $token = DB::instance(DB_NAME)->select_field($q);

        if(!$token) {

            Router::redirect("/users/login/error");

        } else {

            // last parameter says cookie should be available everywhere in the application
            setcookie("token", $token, strtotime('+1 year'), '/');

            Router::redirect("/");
        }

    }

    public function logout() {

        # Generate and save a new token for next login
        $new_token = sha1(TOKEN_SALT.$this->user->email.Utils::generate_random_string());

        // prepare data field to be updated with new token
        $data = Array("token" => $new_token);

        // actually update database
        DB::instance(DB_NAME)->update("users", $data, "WHERE token = '".$this->user->token."'");

        // delete token cookie by setting to a time in the past, logging them out
        setcookie("token", "", strtotime('-1 year'), '/');

        // send them back home
        Router::redirect("/");



    }

	// these function names feature in our url, and what comes after them, their arguments
    public function profile($user_name = NULL) {

        // is user is not logged in, redirect to login
        if(!$this->user) {
            Router::redirect('/users/login');
        }

        // setup view
        $this->template->content = View::instance('v_users_profile');
        $this->template->title = "Profile";
        $this->template->content->user_name = $user_name;


        # CSS/JS includes

        $client_files_head = Array(
            '/css/profile.css'
        );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);


        // render template
        echo $this->template;

    }

} # end of the class