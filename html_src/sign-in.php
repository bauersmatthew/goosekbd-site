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

        $fill_email = '';
        $err = '';
        if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            if(empty($_POST["email"]))
            {
                $err = 'Email left blank.';
            }
            elseif(empty($_POST["password"]))
            {
                $err = 'Password left blank.';
                $fill_email = $_POST["email"];
            }
            else
            {
                # search database
                $conn = new mysqli("localhost", "goosekbd", "g00s3kbd", "goosekbd");
                if($conn->connect_error)
                {
                    $err = 'Something bad happened on our end. Sorry!';
                }
                else
                {
                    $stmt = $conn->prepare(
                        "SELECT passhash FROM `accounts` WHERE email = ?");
                    $stmt->bind_param("s", $_POST["email"]);
                    $stmt->execute();
                    $stmt->bind_result($stored_passhash);
                    if(!$stmt->fetch())
                    {
                        $err = 'Incorrect email or password.';
                        $fill_email = $_POST["email"];
                    }
                    else
                    {
                        if(!password_verify($_POST["password"], $stored_passhash))
                        {
                            $err = 'Incorrect email or password.';
                            $fill_email = $_POST["email"];
                        }
                        else
                        {
                            $_SESSION["email"] = $_POST["email"];
                            header("Location: https://goosekbd.com/profile.php");
                            exit();
                        }
                    }
                }
            }
        }
    ?>
{% endblock %}

{% block ssheets %}
    <link rel="stylesheet" href="css/sign-up.css">
{% endblock %}

{% block title %}Sign In{% endblock %}

{% block body %}
    {{ navbar("sign-in") }}

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <form method="post" action="#" class="endbuf">
                    <fieldset>
                        <legend>Sign In</legend>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="john.doe@example.com"
                                value="<?php print $fill_email; ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-default">Sign In</button>
                </form>
            </div>
        </div>
    </div>

    <div class="container-fluid endbuf">
        <?php
            if($err != '')
            {
                print '
                <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 alert alert-danger fade in">
                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                    ' . $err . '
                </div>';
            }
        ?>
    </div>

{% endblock %}
