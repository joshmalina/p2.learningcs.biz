<!-- log in/sign up prompt -->

<div id = "landing">

    <div id = "left">
        <!-- different splash depending on whether the user has logged in -->
        <?php if(!$user): ?>
        Do you have thoughts?<br><br>
        Enjoy typing stuff?
        <br><br>
        Start grunting now.
        <?php else: ?>
        Welcome back, <?=$user->first_name;?>. <br><br>
        Have you been grunting?
        <br><br>
        Click the animal to grunt.
        <?php endif;?>
    </div>

    <!-- buttons -->
    <div id = "right">

        <!-- made hypertextual -->
        <?php if(!$user): ?>
        <a href = "/users/signup">
            <div class = "button">
                Sign Up
            </div>
        </a>

        <a href="/users/login">
            <div class = "button">
                Login
            </div>
        </a>
        <?php else: ?>
        <a href = "/users/profile">
            <img src="/images/white_fat_mule.png" alt="" height="" width="122">
        </a>
        <?php endif; ?>

    </div>
    <div class = "clear"></div>
</div>

<div id = "plus_one">
    <a href="/index/additional_features">+1</a>
</div>
