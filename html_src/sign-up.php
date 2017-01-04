{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block pre %}
    <?php
        session_start();
        if(isset($_SESSION["email"]))
        {
            header("Location: https://goosekbd.com/profile.php");
            exit();
        }

        $fill_name = '';
        $fill_email = '';
        $err = '';
        $success = FALSE;
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(empty($_POST["name"]))
            {
                $err = 'Name not given.';
                $fill_email = $_POST["email"];
            }
            elseif(!preg_match("/^.{1,255}$/", $_POST["name"]))
            {
                $err = 'Is your name really that long?';
                $fill_email = $_POST["email"];
            }
            elseif(empty($_POST["email"]))
            {
                $err = 'Email not given.';
                $fill_name = $_POST["name"];
            }
            elseif(!preg_match("/^(?=.{3,255}$)[a-zA-Z0-9_.-]+@[a-zA-Z0-9_.-]+/", $_POST["email"]))
            {
                $err = 'Invalid email.';
                $fill_name = $_POST["name"];
            }
            elseif(!preg_match("/(?=.*[0-9])(?=.*[a-zA-Z]).{6,}/", $_POST["pass1"]))
            {
                $err = 'Invalid password.<br />Passwords must be at least 6 characters
                long and contain at least one number and one letter.';
                $fill_name = $_POST["name"];
                $fill_email = $_POST["email"];
            }
            elseif($_POST["pass1"] != $_POST["pass2"])
            {
                $err = 'Passwords don\'t match!';
                $fill_name = $_POST["name"];
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
                        "INSERT INTO `accounts` (email, passhash, name)
                        VALUES (?, ?, ?)");
                    $stmt->bind_param(
                        "sss",
                        $_POST["email"],
                        password_hash($_POST["pass1"], PASSWORD_BCRYPT),
                        $_POST["name"]);
                    if(!$stmt->execute())
                    {
                        $err = 'Looks like that email has already been registered.';
                        $fill_name = $_POST["name"];
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
{% endblock %}

{% block ssheets %}
    <link rel="stylesheet" href="css/sign-up.css">
{% endblock %}

{% block title %}Sign Up{% endblock %}

{% block body %}
    {{ navbar("sign-up") }}

    <form method="post" action="#" class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 endbuf">
        <fieldset>
            <legend>Sign Up</legend>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="John Doe"
                    value="<?php print $fill_name; ?>">
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
