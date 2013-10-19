<?php
class practice_controller extends base_controller{

    public function test_db() {
    /*
        $q = "DELETE FROM users where first_name = 'William'";

        echo $q;

        echo DB::instance(DB_NAME)->query($q);
    */

        $data = Array(
            'first_name'    => 'Roger',
            'last_name'     => 'Morrison',
            'email'         => 'R_mori@aol.dog'
        );

        //$user_id = DB::instance(DB_NAME)->insert('users', $data);

        $user_id = DB::instance(DB_NAME)->delete('users', "WHERE email = 'R_mori@aol.dog'");

    }





    public function test1() {

        // specific to the function we're writing, but eventually these kinds of requires will be replaced by auto loading
        // make sure code had access to class
        //require(APP_PATH.'libraries/image.php');

        // instatiate new object from class
        $imageObj = new Image('http://placekitten.com/1000/1000');

        // now, have access to methods of class

        // use them
        $imageObj->resize(200,200);

        $imageObj->display();

    }

    public  function test2() {

        # Static -- used in a once off manner, without creating an object from the class whose methods we are using
        echo Time::now();
    }






}