{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block ssheets %}
    <link rel="stylesheet" href="css/g01.css">
{% endblock %}

{% block title %}GOOSE 01{% endblock %}

{% block body %}
    {{ navbar("g01") }}

    <div class="row">
        <!-- DESCRIPTION TEXT -->
        <div id="g01-text" class="col-md-5">
            <h2>
                Classic bevels.<br />
                Muted tones.<br />
                Subtle illumination.<br />
            </h2>
        </div>

        <!-- IMAGE -->
        <div id="g01-img" class="col-md-7">
            <img class="img-reponsive" src="res/img/g01/tall.jpg" alt="G01">
        </div>
    </div>

    <!-- TITLE/G-01 -->
    <h1 class="col-md-12 text-centered">
        G-01.
    </h1>
    <h2 class="col-md-12 text-centered">
        Coming Feb. 2017
    </h2>
    <h5 class="col-md-12 text-centered" id="scroll-down">
        (details below)
    </h5>

    <!-- DETAILS -->
    <p class="col-md-12 textwall" id="details">
        With classic bevels, muted tones, and subtle illumination, the G-01 is
        a timeless desktop accessory that strikes boldly wherever it goes.
        <br /><br />
        More specific details coming soon!
    </p>

    <!-- EMAIL FORM -->
    <p class="col-md-12 textwall" id="email-signup">
        Want to recieve email updates? Give us your email below:
    </p>
    <div class="row endbuf">
        <div class="col-md-2"></div>
        <div class="col-md-4">
            <form method="post" action="#email-signup">
                <div class="form-group">
                    <label for="email" class="sr-only">Email:</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter email...">
                </div>
                <button type=submit class="btn btn-default">Submit</button>
            </form>
        </div>
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST")
            {
                if(empty($_POST["email"]))
                {
                    print '
                    <div class="col-md-4 alert alert-danger fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        Oops -- doesn\'t look like you entered anything!
                    </div>';
                }
                else
                {
                    // verify
                    if(!preg_match("/[a-z0-9._-]+@[a-z0-9._-]+/", $_POST["email"]))
                    {
                        print '
                        <div class="col-md-4 alert alert-danger fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Oops -- that doesn\'t look like an email address...
                        </div>';
                    }
                    else
                    {
                        print '
                        <div class="col-md-4 alert alert-success fade in">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            Success!
                        </div>';
                    }
                }
            }
        ?>
    </div>
{% endblock %}
