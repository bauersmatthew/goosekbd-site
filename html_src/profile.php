{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block pre %}
    <?php
        session_start();
        if(!isset($_SESSION["email"]))
        {
            header("Location: https://goosekbd.com/sign-in.php");
            exit();
        }
    ?>
{% endblock %}

{% block title %}My Profile{% endblock %}

{% block body %}
    {{ navbar("profile") }}

    <h2 class="col-md-12">My Orders</h2>

{% endblock %}
