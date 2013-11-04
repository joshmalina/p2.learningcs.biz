<?php
/*
 * This file controls user functions including signup, login, and the profile
 */
class users_controller extends base_controller {

    public function __construct() {
        parent::__construct();

    } # eof

    public function index() {
        Router::redirect('/');

    } # eof

    public function signup() {

        $this->template->content = View::instance('v_users_signup');
        $this->template->title = "Sign Up";

        # CSS/JS includes

        $client_files_head = Array(
            '/css/signup.css'
        );

        // these client files are the css
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        # ERROR CHECKING

        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        $error = false;

            // if all fields are empty
            if(!$_POST) {
                echo $this->template;
                return;
            }

            if ($_POST['password'] != $_POST['password_again'])
            {
                $this->template->content->error = "Your passwords do not match.<br>";
                $error = true;
            }

            # check to see if password is too short
            if (strlen($_POST['password']) < 6)
            {
                $this->template->content->error = "Your password must be at least six characters.<br>";
                $error = true;

            }

            # check for already used email address
            $q = "SELECT email FROM users WHERE email = '".$_POST['email']."'";
            $email_used = DB::instance(DB_NAME)->select_rows($q);
            $email_used = count($email_used);

            if ($email_used > 0)
            {
                $this->template->content->error = "The email inputted has already been registered.<br>";
                $error = true;

            }

            # required fields
            $required_fields = array($_POST['first_name'], $_POST['email'], $_POST['password']);

            if (users_controller::verify_email($_POST['email']) == false)
            {
                $this->template->content->error = "The email inputted doesn't appear to be properly formatted.<br>";
                $error = true;
            }


            # check for empty required fields
            foreach ($required_fields as $required_field)
            {
                // if those required fields have not been filled out
                if (empty($required_field))
                {
                    $this->template->content->error = "At least one required field is empty.<br>";
                    $error = true;
                }
            }

        # if no errors, enter into DB and redirect
        if (!$error)
        {

            // entries for respective data fields
            $_POST['created']   = Time::now();
            $_POST['modified']  = Time::now();
            $_POST['verify_hash'] = md5( rand(0,1000) );

            // encrypt the password
            $_POST['password']  = sha1(PASSWORD_SALT.$_POST['password']);

            // encrypt the token
            $_POST['token']     = sha1(TOKEN_SALT.$_POST['email'].Utils::generate_random_string());

            // created this array because one field, password_again, does not get passed to the database
            $send_to_db = array(
                'created'       => $_POST['created'],
                'modified'      => $_POST['modified'],
                'first_name'    => $_POST['first_name'],
                'last_name'     => $_POST['last_name'],
                'password'      => $_POST['password'],
                'email'         => $_POST['email'],
                'token'         => $_POST['token'],
                'verify_hash'   => $_POST['verify_hash']
            );

            // this is how we actually get our data into the database
            $user_id = DB::instance(DB_NAME)->insert('users', $send_to_db);

            # send a confirmation email that they have signed up

                # Build a multi-dimension array of recipients of this email
                $to[] = Array("name" => $_POST['first_name'], "email" => $_POST['email']);

            # Build a single-dimension array of who this email is coming from
                $from = Array("name" => APP_NAME, "email" => APP_EMAIL);

                # Subject
                $subject = "Welcome to Gruntr";

                # You can set the body as just a string of text
                $body = "Hi " . $_POST['first_name'].", thank you for signing up at Gruntr. You are almost finished! ";
                $body .= "Please click the link below to confirm your registration: ";
                $body .= 'p2.learningcs.biz/users/email_signup_verification?email='.$_POST['email'].'&hash='.$_POST['verify_hash'].''; // Our message above including the link

                # Build multi-dimension arrays of name / email pairs for cc / bcc if you want to
                $bcc = "info@laoshilist.com";

                # With everything set, send the email
                $email = Email::send($to, $from, $subject, $body, true);

            // redirect to success page
            Router::redirect('/users/signup_success');

        }

        // if errors present
        else {
            echo $this->template;
        }


    } # eof

    // this function sends information to a view that tells the user to check their email for an account activation
    public function signup_success() {
        $this->template->content = View::instance('v_users_signup_success');
        $this->template->title = "Signup Almost Complete";
        echo $this->template;

    } # eof

    public function email_signup_verification($message = NULL) {

        $this->template->content = View::instance('v_users_email_signup_verification');
        $this->template->title = "Verify Your Email";
        echo $this->template;
        $this->template->content->message = $message;


        if(isset($_GET['email']) && !empty($_GET['email']) AND isset($_GET['hash']) && !empty($_GET['hash'])){
            // Verify data

            $_GET = DB::instance(DB_NAME)->sanitize($_GET);

            $email = ($_GET['email']); // Set email variable -- not sanitized against
            $hash = ($_GET['hash']); // Set hash variable -- not sanitized

            $match = DB::instance(DB_NAME)->select_rows("SELECT email, verify_hash, verified FROM users WHERE email='".$email."' AND verify_hash='".$hash."' AND verified='0'");
            $match = count($match);

            if($match > 0){
                // We have a match, activate the account

                $q = array('verified' => 1);
                $verify_user = DB::instance(DB_NAME)->update('users', $q, "WHERE email='".$email."' AND verify_hash='".$hash."' AND verified='0'");
                echo $this->template->content->message = "Your account has been activated, you can now login";

            } else {
                // No match -> invalid url or account has already been activated.
                echo $this->template->content->message = "The url is either invalid or you already have activated your account.";


            }

        }else{
            // Invalid approach
            echo $this->template->content->message = "Invalid approach, please use the link that has been send to your email.";
        }

    } # eof

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

        // include css file
        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // render template
        echo $this->template;

    } # eof

    public function p_login() {

        // sanitize
        $_POST = DB::instance(DB_NAME)->sanitize($_POST);

        // hash submitted password
        $_POST['password'] = sha1(PASSWORD_SALT.$_POST['password']);

        // make sure user has verified account
        $sql = "SELECT * FROM users WHERE verified = 1 AND email = '".$_POST['email']."'";
        $verify = DB::instance(DB_NAME)->select_row($sql);

        // search db for email/password, return token if available
        $q = "SELECT token FROM users WHERE email = '".$_POST['email']."' AND password = '".$_POST['password']."'";
        $token = DB::instance(DB_NAME)->select_field($q);


        // if email and username do not agree
        if(!$token) {

            Router::redirect("/users/login/email_username_mismatch");

        } else {

            if(!$verify) {

                // has not verified their account yet
                Router::redirect("/users/login/account_not_verified");

            } else {

                // allow them to login
                // note: last parameter makes cookie available everywhere in the application
                setcookie("token", $token, strtotime('+1 year'), '/');

                // redirect to home
                Router::redirect("/");

            }

        }

    } # eof

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

    } # eof

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

        # total number of grunts
        $q = "SELECT post_id FROM posts WHERE user_id = ".$this->user->user_id;
        $post_num = DB::instance(DB_NAME)->select_rows($q);
        $post_num = count($post_num);

        // send to view
        $this->template->content->post_num = $post_num;

        # CSS/JS includes

        $client_files_head = Array(
            '/css/profile.css'
        );

        $this->template->client_files_head = Utils::load_client_files($client_files_head);

        // render template
        echo $this->template;

    } # eof

    // make sure email is written in the expected format
    public static function verify_email($email){

        if(!preg_match('/^[_A-z0-9-]+((\.|\+)[_A-z0-9-]+)*@[A-z0-9-]+(\.[A-z0-9-]+)*(\.[A-z]{2,4})$/',$email)){
            return false;
        } else {
            return $email;
        }
    } # eof

} # end of the class