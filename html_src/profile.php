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

    <h2 class="col-md-9 col-md-offset-3">My Orders</h2>

    <div class="row">
        <div class="col-sm-1 col-sm-offset-2 col-xs-2 col-xs-offset-1"></div>
        <h4 class="col-xs-1">Order Number</h4>
        <h4 class="col-xs-2">Product x Quantity</h4>
        <h4 class="col-xs-2">Order Date</h4>
        <h4 class="col-xs-2">Status</h4>
    </div>

    <?php
        function translate_status_code($code)
        {
            if($code == 'o')
            {
                return 'Order processed on ';
            }
            elseif($code == 'p')
            {
                return 'Product in warehouse from ';
            }
            elseif($code == 's')
            {
                return 'Product shipped on ';
            }
            elseif($code == 'a')
            {
                return 'Product arrived on ';
            }
            else
            {
                return 'Autism arrived on ';
            }
        }

        # get order details from database
        $conn = new mysqli("localhost", "goosekbd", "g00s3kbd", "goosekbd");
        $stmt = $conn->prepare(
            "SELECT id, product, quantity, status, date_ordered, date_statuschange FROM `orders` WHERE email = ?");
        $stmt->bind_param("s", $_SESSION["email"]);
        $stmt->execute();
        $stmt->bind_result($order_id, $product_id, $quantity, $status, $date_ordered, $date_statuschange);

        $stmt_img = $conn->prepare(
            "SELECT name, icon FROM `products` WHERE id = ?");
        $stmt->bind_param($product_id);
        $stmt->bind_result($product_name, $product_icon);

        while($stmt->fetch())
        {
            # get product icon
            $stmt_img->execute();
            $stmt_img->fetch();

            print '
            <div class="row">
                <img class="col-sm-1 col-sm-offset-2 col-xs-2 col-xs-offset-1 img-responsive" src="' . $product_icon . '" alt="Product image">
                <h4 class="col-xs-1">#' . $order_id . '</h4>
                <h4 class="col-xs-2">' . $product_name . ' x ' . $quantity . '</h4>
                <h4 class="col-xs-2">' . $date_ordered . '</h4>
                <h4 class="col-xs-2">' . translate_status_code($status) . $date_statuschange . '</h4>
            </div>';
        }
    ?>

{% endblock %}
