

    <div class = "form" >

        Sign up to start grunting.

        <br><br><label>*Fields marked with an asterisk are required.</label>

        <br><br>
        <form method = 'POST' action = '/users/signup'>

            <label for "first_name">First Name*</label><br>
            <input type="text" name="first_name" value="<?php echo $_POST['first_name'];?>" autofocus>
            <br><br>

            <label for "last_name">Last Name</label><br>
            <input type="text" name="last_name" value="<?php echo $_POST['last_name'];?>">
            <br><br>

            <label for "Email">Email*</label><br>
            <input type="text" name="email" value="<?php echo $_POST['email'];?>">
            <br><br>

            <label for "password">Password*</label><br>
            <input type="password" name="password">
            <br><br>

            <label for "password_again">Please input your password again*</label><br>
            <input type="password" name="password_again">
            <br><br>

            <input type="submit" value="Sign up"><br><br>

            <?php if(isset($error)) echo $error; ?>

        </form>
    </div>
