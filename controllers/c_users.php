<?php
/*
 * This file controls user functions including signup, login, and the profile
 */
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

        $errors_array = array("dogs", "cats", $_POST['first_name']);

        //Pass data to the view
        $this->template->content->errors_array = $errors_array;

        # CSS/JS includes

        $client_files_head = Array(
            '/css/signup.css'
        );

        // these client files are the css
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // sending the actual content
        echo $this->template;

    }

    public function p_signup() {

        /*// using an input and validate class from library
        if (Input::exists()) {
            $validate = new Validate();
            $validation = $validation->check($_POST, array(
                'first_name' => array(
                    'required'  => true,
                    'min'       => 1,
                    'max'       => 20,
                ),
                'last_name' => array(
                    'required'  => false,
                    'min'       => 0,
                    'max'       => 20,
                ),
                'email' => array(
                    'required'  => true,
                    'min'       => 5,
                    'max'       => 50,
                    // make sure that this email has not already been registered
                    'unique'    => 'email'
                ),
                'password' => array(
                    'required'  => true,
                    // make sure that passwords are at least six characters long
                    'min'       => 6,

                // when up and running, maybe include password_again for checking
                )

            ));

            if ($validation->passed()) {
                // register user
            } else {
                //output errors
            }


        }*/

        # ERROR CHECKING

            $errors_array = array();

            # keep track of total number of errors
            $signup_errors = 0;

            # check for already used email address
            $q = "SELECT email FROM users WHERE email = '".$_POST['email']."'";
            $email_used = DB::instance(DB_NAME)->select_rows($q);
            $email_used = count($email_used);

            if ($email_used > 0)
            {
                $errors_array[] = "The email inputted has already been registered.";
                $signup_errors++;
            }

            # required fields
            $required_fields = array($_POST['first_name'], $_POST['email'], $_POST['password']);

            # check for empty required fields
            foreach ($required_fields as $required_field)
            {
                // if those required fields have not been filled out
                if (!isset($required_field))
                {
                    $signup_errors++;
                    $errors_array[] = "At least one required field is empty.";
                }
            }

            # check to see if password is too short
            if (strlen($_POST['password']) < 7)
            {
                $errors_array[] = "Your password must be at least six characters.";
                $signup_errors++;
            }

            # if any errors, report to user
            if ($signup_errors > 0)
            {
                Router::redirect('/users/signup/error');

            }

        # END ERROR CHECKING

        // entries for respective data fields
        $_POST['created']   = Time::now();
        $_POST['modified']  = Time::now();

        // encrypt the password
        $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);

        // encrypt the token
        $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

        // this is how we actually get our data into the database
        $user_id = DB::instance(DB_NAME)->insert('users', $_POST);


        # CSS/JS includes

        // what files we want to include
        $client_files_head = Array(
            '/css/signup.css'
        );

        // getting those files actually included
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // setting up the view
        $this->template->content = View::instance('v_users_p_signup');

        // defining the title
        $this->template->title = "Signed Up";

        // sending the content
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


        // from our Post library file, to list all the posts from members that this user follows
        $this->template->content->posts = Post::posts_from_users_followed($this->user->user_id);

        // also from Post library file, to get all users
        $this->template->content->users = Post::get_all_users();

        // also from Post library file, to get all users so that they can be toggled for follow/unfollow
        $this->template->content->connections = Post::follow_unfollow($this->user->user_id);

        # when user joined
        $q = "SELECT created FROM users WHERE user_id = ".$this->user->user_id;
        $joined = DB::instance(DB_NAME)->select_field($q);
        $joined = date('Y', strtotime($joined));

        # pass to view
        $this->template->content->joined = $joined;

        # total number of grunts
        $q = "SELECT post_id FROM posts WHERE user_id = ".$this->user->user_id;
        $post_num = DB::instance(DB_NAME)->select_rows($q);

        $post_num = count($post_num);

        $this->template->content->post_num = $post_num;

        # CSS/JS includes

        $client_files_head = Array(
            '/css/profile.css'
        );
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // render template
        echo $this->template;

    }

} # end of the class