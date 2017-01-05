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
        elseif(empty($_GET["v"]))
        {
            header("Location: https://goosekbd.com/index.php");
            exit();
        }
        else
        {
            $conn = new mysqli("localhost", "goosekbd", "g00s3kbd", "goosekbd");
            $verify_val = $_GET["v"];
            $stmt = $conn->prepare(
                "SELECT email, passhash, name FROM `accounts-unverified` WHERE verify_val = ?");
            $stmt->bind_param("s", $verify_val);
            $stmt->execute();
            $stmt->store_result();
            if($stmt->num_rows != 0)
            {
                /* get values */
                $stmt->bind_result($email, $passhash, $name);
                $stmt->fetch();
                $stmt->free_result();
                $stmt->close();

                /* remove from accounts-unverified */
                $stmt = $conn->prepare(
                    "DELETE FROM `accounts-unverified` WHERE verify_val = ?");
                $stmt->bind_param("s", $verify_val);
                $stmt->execute();
                $stmt->close();

                /* insert into accounts */
                $stmt = $conn->prepare(
                    "INSERT INTO `accounts` (email, passhash, name) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $email, $passhash, $name);
                $stmt->execute();
                $stmt->close();

                $conn->close();
                $_SESSION["email"] = $email;
                $_SESSION["name"] = $name;
                header("Location: https://goosekbd.com/profile.php?verify=yes");
                exit();
            }
            else
            {
                $conn->close();
            }
        }
    ?>
{% endblock %}

{% block body %}
    {{ navbar("sign-up") }}

    <h2>
        Verification code invalid or expired. <br />
        If expired, try again to <a href="https://goosekbd.com/sign-up.php">create account</a>.
    </h2>
{% endblock %}
