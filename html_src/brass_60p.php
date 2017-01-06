{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block ssheets %}
    <link rel="stylesheet" href="css/brass_60p.css">
{% endblock %}

{% block title %}Brass Plates{% endblock %}

{% block body %}
    {{ navbar("products") }}

    <!-- CLASSIC DRAMATIC TOP TEXT -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-5 text-centered" id="mats">
                <h2 id="crossout-mats">
                    Plastic.<br />
                    Aluminum.<br />
                    Carbon Fiber.<br />
                </h2>
                <h1 id="brass-mat">
                    Brass.
                </h1>
            </div>
            <div class="col-md-7">
                <img class="img-responsive" src="res/img/brass_60p/topdown.png" alt="brass plate">
            </div>
        </div>
    </div>

    <!-- DETAILS -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <p class="textwall" id="details">
                    Characteristic of Korean customs, brass plates offer boards a unique
                    sound, weight, and feel.
                    However, despite significant growth in the custom toplate market
                    brass plates still remain as quite an elusive option for
                    even the more serious builders.
                    <br /><br />
                    Here at Goose Keyboards, we'd like to change that. 2017's a new year;
                    we think it's time for a new top-plate as well.
                </p>
            </div>
        </div>
    </div>

    <div class="container-fluid endbuf">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
                <form method="get" action="buy.php?prod=brass60p" class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 endbuf">
                    <button type="submit" class="btn btn-primary btn-block outline" id="buy-btn">
                        Click Here to Order!
                    </button>
                </form>
            </div>
        </div>
    </div>
{% endblock %}
