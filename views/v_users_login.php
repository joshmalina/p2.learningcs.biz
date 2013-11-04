<div class = "form">
    Login<br><br>
    <form method = "POST" action = "/users/p_login">

        <label for "email">Email</label><br>
        <input type="text" name="email" autofocus>

        <br><br>

        <label for "password">Password</label><br>
        <input type="password" name="password">

        <br><br>

        <?php if(isset($error) && $error == 'email_username_mismatch'): ?>
            <div class='error'>
                You were not logged in. Please double check your email and password.
            </div>
            <br>
        <?php endif ?>

        <?php if(isset($error) && $error == 'account_not_verified'): ?>
            <div class='error'>
                You were not logged in. Please activate your account to login.
            </div>
            <br>
        <?php endif ?>

        <input type="submit" value="Log in">

    </form>
</div>