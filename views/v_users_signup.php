<div class = "form" >
    Sign up to start grunting.

    <br><br>
    <form method = 'POST' action = '/users/p_signup'>

        <label for "first_name">First Name*</label><br>
        <input type="text" name="first_name">
        <br><br>

        <label for "last_name">Last Name</label><br>
        <input type="text" name="last_name">
        <br><br>

        <label for "Email">Email*</label><br>
        <input type="text" name="email">
        <br><br>

        <label for "password">Password*</label><br>
        <input type="password" name="password">
        <br><br>

        <label for "password">Confirm Password*</label><br>
        <!-- <input type="password" name="confirm_password"> -->

        <?php if(!empty($errors_array)): ?>
            <div class='error'>
                <?php foreach ($errors_array as $error)
                {
                    echo $error . "<br>";
                }
                ?>
            </div>
            <br>
        <?php endif ?>

        <br><br><label>*Fields marked with an asterisk are required.</label><br><br>

        <input type="submit" value="Sign up">


    </form>
</div>