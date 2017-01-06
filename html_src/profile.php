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

    <?php
        if(!empty($_GET["verify"]))
        {
            print '
            <div class="col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1 alert alert-success fade in">
                <a href="#" class="close" data-dismiss="alert">&times;</a>
                Account verified successfully! Thanks for signing up.
            </div>';
        }
    ?>

    <h2 class="col-md-11 col-md-offset-1" id="orders-title">My Orders</h2>

    <table class="table col-md-10 col-md-offset-1">
        <thead>
            <tr>
                <th></th> <!-- the image -->
                <th>Order #</th>
                <th>Product</th>
                <th>Order Date</th>
                <th>Status</th>
            </tr>
        <tbody>
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
                    <tr>
                        <td><img class="img-responsive" src="' . $product_icon . '" alt="Product image"></td>
                        <td>#' . $order[0] .'</td>
                        <td>' . $product_name . ' &times;' . $order[2] . '</td>
                        <td>' . $order[4] . '</td>
                        <td>' . translate_status_code($order[3]) . $order[5] . '</td>
                    </tr>';
                }

                $stmt->close();
                $conn->close();
            ?>
        </tbody>
    </table>

{% endblock %}
