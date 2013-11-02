<?php
/*
 *
 * For all things post
 */

class Post {

    public static function get_posts_by_user($user_id) {

        $q = 'SELECT * FROM posts WHERE user_id = '.$user_id;

        $post = DB::instance(DB_NAME)->select_rows($q);

        return $post;

    }


    public static function posts_from_users_followed($user_id) {

        # Query
        $q = 'SELECT
        posts.content,
        posts.created,
        posts.user_id AS post_user_id,
        users_users.user_id AS follower_id,
        users.first_name,
        users.last_name
        FROM posts
        INNER JOIN users_users
        ON posts.user_id = users_users.user_id_followed
        INNER JOIN users
        ON posts.user_id = users.user_id
        WHERE users_users.user_id = '.$user_id.'
        ORDER BY posts.created DESC';


        # Run the query, store the results in the variable $posts
        $posts = DB::instance(DB_NAME)->select_rows($q);

        return $posts;

    }

    public static function get_all_users() {

        # Build the query to get all the users
        $q = "SELECT *
            FROM users";

        # Execute the query to get all the users.
        # Store the result array in the variable $users
        $users = DB::instance(DB_NAME)->select_rows($q);

        return $users;

    }


    public static function follow_unfollow($user_id) {

        # Build the query to figure out what connections does this user already have?
        # I.e. who are they following
        $q = "SELECT *
            FROM users_users
            WHERE user_id = ".$user_id;

        # Execute this query with the select_array method
        # select_array will return our results in an array and use the "users_id_followed" field as the index.
        # This will come in handy when we get to the view
        # Store our results (an array) in the variable $connections
        $connections = DB::instance(DB_NAME)->select_array($q, 'user_id_followed');

        return $connections;

    }



}