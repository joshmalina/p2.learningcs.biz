<div class = "form">
    Login<br><br>
    <form method = "POST" action = "/users/p_login">

        <label for "email">Email</label><br>
        <input type="text" name="email">

        <br><br>

        <label for "password">Password</label><br>
        <input type="password" name="password">

        <br><br>

        <?php if(isset($error)): ?>
            <div class='error'>
                You were not logged in. Please double check your email and password.
            </div>
            <br>
        <?php endif ?>

        <input type="submit" value="Log in">

    </form>
</div>