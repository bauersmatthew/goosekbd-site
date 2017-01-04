{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block ssheets %}
    <link rel="stylesheet" href="css/sign-up.css">
{% endblock %}

{% block title %}Sign Up{% endblock %}

{% block body %}
    {{ navbar("sign-up") }}

    <?php
        $fill_user = '';
        $fill_email = '';
        $err = '';
        $success = FALSE;
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(empty($_POST["username"]))
            {
                $err = 'Username not given.';
                $fill_email = $_POST["email"];
            }
            elseif(!preg_match("/[a-zA-Z0-9._-]{1,255}/", $_POST["username"]))
            {
                $err = 'Invalid username; must containing only alphanumeric characters
                or ".", "_", or "-"';
                $fill_email = $_POST["email"];
            }
            elseif(empty($_POST["email"]))
            {
                $err = 'Email not given.';
                $fill_user = $_POST["username"];
            }
            elseif(!preg_match("/^(?=.{3,255}$)[a-zA-Z0-9_.-]+@[a-zA-Z0-9_.-]+/", $_POST["email"]))
            {
                $err = 'Invalid email.';
                $fill_user = $_POST["username"];
            }
            elseif(!preg_match("/(?=.*[0-9])(?=.*[a-zA-Z]).{6,}/", $_POST["pass1"]))
            {
                $err = 'Invalid password.<br />Passwords must be at least 6 characters
                long and contain at least one number and one letter.';
                $fill_user = $_POST["username"];
                $fill_email = $_POST["email"];
            }
            elseif($_POST["pass1"] != $_POST["pass2"])
            {
                $err = 'Passwords don\'t match!';
                $fill_user = $_POST["username"];
                $fill_email = $_POST["email"];
            }
            else
            {
                # ok; add to database
                $conn = new mysqli("localhost", "goosekbd", "g00s3kbd", "goosekbd");
                if($conn->connect_error)
                {
                    $err = 'Something bad happened on our end. Sorry!';
                }
                else
                {
                    $stmt = $conn->prepare(
                        "INSERT INTO `accounts` (username, passhash, email)
                        VALUES (?, ?, ?)");
                    $stmt->bind_param(
                        "sss",
                        $_POST["username"],
                        password_hash($_POST["pass1"], PASSWORD_BCRYPT),
                        $_POST["email"]);
                    if(!$stmt->execute())
                    {
                        $err = 'Looks like either that username or email has already been registered.';
                        $fill_user = $_POST["username"];
                        $fill_email = $_POST["email"];
                    }
                    else
                    {
                        $success = TRUE;
                    }
                    $stmt->close();
                }
            }
        }
    ?>

    <form method="post" action="#" class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 endbuf">
        <fieldset>
            <legend>Sign Up</legend>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" placeholder="johndoe1"
                    value="<?php print $fill_user; ?>">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" class="form-control" placeholder="john.doe@example.com"
                    value="<?php print $fill_email; ?>">
            </div>
            <div class="form-group">
                <label for="pass1">Password</label>
                <input type="password" name="pass1" id="pass1" class="form-control">
            </div>
            <div class="form-group">
                <label for="pass2">Confirm Password</label>
                <input type="password" name="pass2" id="pass2" class="form-control">
            </div>
            <button type="submit" class="btn btn-default">Sign Up</button>
    </form>

    <div class="endbuf">
        <?php
            if($err != '')
            {
                print '
                <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    ' . $err . '
                </div>';
            }
            elseif($success == TRUE)
            {
                print '
                <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 alert alert-success fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    Success! <a href="sign-in.php">Click here to sign in.</a>
                </div>';
            }
        ?>
    </div>
{% endblock %}
