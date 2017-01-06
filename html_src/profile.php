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

{% block ssheets %}
    <link rel="stylesheet" href="css/profile.css">
{% endblock %}

{% block title %}My Profile{% endblock %}

{% block body %}
    {{ navbar("profile") }}

    <h2 class="col-md-11 col-md-offset-1" id="orders-title">My Orders</h2>

    <div class="row">
        <div class="col-xs-2 col-xs-offset-1"></div>
        <p class="col-xs-2">Order Number</p>
        <p class="col-xs-2">Product x Quantity</p>
        <p class="col-xs-2">Order Date</p>
        <p class="col-xs-2">Status</p>
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

        $orders = [];
        while($stmt->fetch())
        {
            $orders[] = array($order_id, $product_id, $quantity, $status, $date_ordered, $date_statuschange);
        }
        $stmt->close();

        $stmt = $conn->prepare(
            "SELECT name, icon FROM `products` WHERE id = ?");
        $stmt->bind_param("s", $order_id);
        $stmt->bind_result($product_name, $product_icon);

        foreach($orders as $order)
        {
            # get product icon
            $order_id = $order[0];
            $stmt->execute();
            $stmt->fetch();

            print '
            <div class="col-xs-10 col-xs-offset-1 bottom-border"></div>
            <div class="row">
                <img class="col-sm-2 col-sm-offset-1 col-xs-2 col-xs-offset-1 img-responsive" src="' . $product_icon . '" alt="Product image">
                <p class="col-xs-2">#' . $order[0] . '</p>
                <p class="col-xs-2">' . $product_name . ' x ' . $order[2] . '</p>
                <p class="col-xs-2">' . $order[4] . '</p>
                <p class="col-xs-2">' . translate_status_code($order[3]) . $order[5] . '</p>
            </div>';
        }

        $stmt->close();
        $conn->close();
    ?>

{% endblock %}
