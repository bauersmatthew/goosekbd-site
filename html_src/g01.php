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
        The G-01 is a keyboard kit that offers a unique high-profile aluminum case with
        RGB midlighting. This group buy is being run in collaboration with
        <a href="http://www.mechkeys.ca">mechkeys.ca</a> &mdash; go check them out!
        <br /><br />
    </p>
    <div class="row" id="speclist">
        <div class="col-md-6">
            <p>General Specs:</p>
            <ul class="dashed">
                <li>High profile</li>
                <li>60% form-factor</li>
                <li>Anodized, powder coated top and bottom pieces</li>
                <li>Top and bottom aluminum pieces with a variety of high quality finishes</li>
                <li>RGB midglow-compatible acrylic middle</li>
                <li>ps2avRGB PCB</li>
            </ul>
        </div>
        <div class="col-md-1"></div>
        <div class="col-md-5" id="speclist-r1c2">
            <p>Case Finish Options:</p>
            <ul class="dashed">
                <li>Anodized Black</li>
                <li>Anodized Silver</li>
                <li>Anodized Red</li>
                <li>Powder-coated Black</li>
                <li>Powder-coated White</li>
            </ul>
        </div>
    </div>
    <div class="col-md-12 textwall">
        <p>
            <br />
            The G-01 kit will be sold in any combination of the following parts:
        </p>
        <ul class="dashed">
            <li>60% Case <strong>(REQUIRED)</strong></li>
            <li>ps2avRGB PCB</li>
            <li>Aluminum or carbon fiber plate (ANSI and ISO both available)</li>
        </ul>
        <p>
            <i>Note: gateron switches may also be offered.</i>
        </p>
    </div>

    <!-- EMAIL FORM -->
    <p class="col-md-12 textwall" id="email-signup">
        <br />
        Want to recieve email updates? Give us your email below:
    </p>
    <div class="row">
        <div class="col-xs-2"></div>
        <form method="post" action="#email-signup" class="col-xs-4">
            <div class="form-group">
                <label for="email" class="sr-only">Email:</label>
                <input type="email" name="email" class="form-control" placeholder="Enter email...">
            </div>
            <button type=submit class="btn btn-default">Submit</button>
        </form>
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
                        // connect to database
                        $conn = new mysqli("localhost", "goosekbd", "g00s3kbd", "goosekbd");
                        if($conn->connect_error)
                        {
                            print '
                            <div class="col-md-4 alert alert-danger fade in">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                Something bad happened on our end. Sorry!
                            </div>';
                        }
                        else
                        {
                            // insert into table
                            $sql = "INSERT INTO `g01-mailing-list` (email)
                            VALUES ('" . $_POST["email"] . "')";
                            if($conn->query($sql) == TRUE)
                            {
                                print '
                                <div class="col-md-4 alert alert-success fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    Success -- thanks for the support!
                                </div>';
                            }
                            else
                            {
                                // happens when the person has already joined the mailing list
                                print '
                                <div class="col-md-4 alert alert-info fade in">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                    Looks like you already signed up! &#9786;
                                </div>';
                            }
                        }

                        $conn->close();
                    }
                }
            }
        ?>
    </div>

    <div class="col-md-12 endbuf text-centered" id="drama-text">
        <i>
            <br />
            <br />
            With classic bevels, muted tones, and subtle illumination, the G-01 is
            a timeless desktop accessory that strikes boldly wherever it goes.
        </i>
    </div>
{% endblock %}
