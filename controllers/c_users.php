<?php
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();
        echo "users_controller construct called<br><br>";
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

        echo "Thank you for signing up!";
    }

    public function login() {

        // set up view
        $this->template->content = View::instance('v_users_login');

        // title
        $this->template->title = "Login";

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

            Router::redirect("/users/login");


        } else {

            // last parameter says cookie should be available everywhere in the application
            setcookie("token", $token, strtotime('+1 year'), '/');

            Router::redirect("/");
        }

    }

    public function logout() {
        echo "This is the logout page";
    }

	// these function names feature in our url, and what comes after them, their arguments
    public function profile($user_name = NULL) {

        $this->template->content = View::instance('v_users_profile');
        $this->template->title = "Profile";
        $this->template->content->user_name = $user_name;
        echo $this->template;

    }

} # end of the class