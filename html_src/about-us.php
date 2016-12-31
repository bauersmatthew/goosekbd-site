{% extends "templates/page.html" %}
{% from "templates/macros.html" import navbar %}

{% block title %}About Us{% endblock %}

{% block body %}
    {{ navbar("about us") }}

    <div class="col-md-12 textwall endbuf">
        Goose Keyboard Co. is composed of
        Siddhardha Maligireddy (/u/misterbinlee)
        Nikolai Oh (/u/t-mootypus),
        and Matthew Bauer (/u/cyangekko).
        <br /><br />
        To contact us, shoot an email to: <code>support@goosekbd.com</code>
    </div>
{% endblock %}
