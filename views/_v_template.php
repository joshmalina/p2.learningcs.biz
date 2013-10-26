<!DOCTYPE html>
<html>
<head>
	<title><?php if(isset($title)) echo $title; ?></title>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <!-- Common CSS/JSS -->
    <link rel="stylesheet" href="/css/p2_main.css" type="text/css">
					
	<!-- Controller Specific JS/CSS -->
	<?php if(isset($client_files_head)) echo $client_files_head; ?>
	
</head>

<body>

    <!-- BEGIN: Sticky Header -->
    <div id="header_container">
        <div id="header">
        <img src="/images/fat_mule.png" alt="" height="" width="62">
        <a href = "/">gruntr</a> <!-- the best way to let people know that things are happening to you -->

            <div id='menu'>
                <!-- Menu for users who are logged in -->
                <?php if($user): ?>

                    <a href='/users/logout'>Logout</a>
                    <a href='/users/profile'>Profile</a>

                <!-- Menu options for users who are not logged in -->
                <?php else: ?>

                    <a href='/users/signup'>Sign up</a>
                    <a href='/users/login'>Log in</a>

                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- END: Sticky Header -->

    <!-- BEGIN: Page Content -->
    <div id = "container">
        <div id = "content">
            <?php if(isset($content)) echo $content; ?>
        </div>
    </div>
    <!-- END: Page Content -->


    <?php if(isset($client_files_body)) echo $client_files_body; ?>
</body>
</html>