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
            <img src="/images/white_fat_mule.png" alt="logo" width="122">
        </a>
        <?php endif; ?>

    </div>
    <div class = "clear"></div>
</div>

<div class = "extra_features">
    <ul><h3>Plus Ones</h3></ul>
    <ul>
        <h4>signup form validation</h4>
        <dd>required fields</dd>
        <dd> confirm password</dd>
        <dd> password length</dd>
        <dd> email formatted correctly</dd>
        <dd> email already registered</dd>
    </ul>
    <ul>
        <h4>email to users upon successful registration</h4>
        <dd>includes hash to verify account</dd>
        <dd>account verification required for login</dd>
        <dd>error checking for verification at login</dd>
    </ul>
</div>
