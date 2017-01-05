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
                    # check if email already exists in accounts database
                    # (email already registered)
                    $stmt = $conn->prepare(
                        "SELECT 1 FROM `accounts` where email = ?");
                    $stmt->bind_param("s", $_POST["email"]);
                    $stmt->execute();
                    $stmt->store_result();
                    if($stmt->num_rows != 0)
                    {
                        $err = 'Looks like that email has already been registered.';
                        $fill_name = $_POST["name"];
                        $fill_email = $_POST["email"];
                    }
                    else
                    {
                        # add to accounts-unverified database
                        $verify_val = substr(md5(openssl_random_pseudo_bytes(32)), 0, 20);
                        $expire = time()+3600; # 1 hour from now
                        $passhash = password_hash($_POST["pass1"], PASSWORD_BCRYPT);
                        $stmt = $conn->prepare(
                            "INSERT INTO `accounts-unverified` (verify_val, expire, email, passhash, name)
                            VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param(
                            "sisss",
                            $verify_val,
                            $expire,
                            $_POST["email"],
                            $passhash,
                            $_POST["name"]);
                        if(!$stmt->execute())
                        {
                            # this happens when someone tries to register their email
                            # twice without verifying it.
                            $err = 'Looks like that email has already been registered.';
                            $fill_name = $_POST["name"];
                            $fill_email = $_POST["email"];
                        }
                        else
                        {
                             # send verification email
                             /*mail(
                                $_POST["email"],

                                'Verify Your GooseKBD Account',

                                'Click the link below to verify your account.' . "\r\n" .
                                'If you did not request an account at goosekbd.com, ignore this email.' . "\r\n\r\n" .
                                'https://goosekbd.com/verify.php?v=' . $verify_val . "\r\n\r\n" .
                                'Thank you!' . "\r\n" . '  --  The GooseKBD Team',

                                'From: "GooseKBD" <verify@goosekbd.com>' . "\r\n" .
                                'X-Mailer: PHP/' . phpversion() . "\r\n" .
                                'MIME-Version: 1.0'
                            );*/
                            $success = TRUE;
                        }
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

{% block head %}
<script type="text/javascript">
    $(document).ready(function(){
        $('#verifyModal').modal('show');
    });
</script>
{% endblock %}

{% block body %}
    <?php
        if($success == TRUE)
        {
            print '
            <div class="modal fade" id="verifyModal">
                <div class="modal-dialogue modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Verify Your Email</h3>
                        </div>
                        <div class="modal-body">
                            <h4>Success &mdash; you\'re almost there!</h4>
                            <p>
                                Before you can log in, we need to verify your email. <br />
                                We\'ve sent an email to
                                <code>' . $_POST["email"] . '</code>;
                                it should arrive soon**. When it does,
                                just click the link in it to log in! <br /><br />
                                <small>**If it doesn\'t after 30 minutes or so,
                                contact us at <code>support@goosekbd.com</code>.</small>
                            </p>
                        </div>
                    </div>
                </div>
            </div>';
        }
    ?>

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
        ?>
    </div>
{% endblock %}
