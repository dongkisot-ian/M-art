<?php
require_once('./initialize.php');
require_once('./classes/DBConnection.php');
require_once('./config.php');
require_once('./functions/common_functions.php'); 

function checkout($conn) {
    $ip = getIPAddress();
    $select_cart_items = "
        SELECT c.*, p.title, p.short_description, p.selling_price
        FROM cart_details c
        JOIN product_list p ON c.product_id = p.id
        WHERE c.ip_address = '$ip'";

    $cart_items = mysqli_query($conn, $select_cart_items);

    if (!$cart_items) {
        die('Query Failed: ' . mysqli_error($conn));
    }
    
    $total_amount = 0; 
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Checkout</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
            }
            .checkout-container {
                background: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                width: 100%;
                max-width: 800px;
                text-align: center;
            }
            .cart-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 10px;
                border-bottom: 1px solid #ccc;
                padding-bottom: 10px;
            }
            .cart-item img {
                width: 50px;
                height: 50px;
                object-fit: cover;
                border-radius: 4px;
            }
            .cart-item-details {
                flex: 1;
                text-align: left;
                margin-left: 10px;
            }
            .cart-item-title {
                font-weight: bold;
            }
            .total-amount {
                font-size: 1.2em;
                font-weight: bold;
                margin-top: 20px;
            }
            .checkout-button {
                margin-top: 20px;
                padding: 10px 20px;
                font-size: 1em;
                color: #fff;
                background-color: #28a745;
                border: none;
                border-radius: 4px;
                cursor: pointer;
            }
            .checkout-button:hover {
                background-color: #218838;
            }
        </style>
    </head>
    <body>
        <div class="checkout-container">
            <h3 class="fw-bolder text-center">Checkout</h3>
            <?php
            if (mysqli_num_rows($cart_items) > 0) {
                echo '<div class="cart-items">';
                while ($row = mysqli_fetch_assoc($cart_items)) {
                    $total_amount += $row['selling_price'] * $row['quantity']; // Calculate ttl amount
                    echo '<div class="cart-item">';
                    echo '<div class="cart-item-details">';
                    echo '<h5 class="cart-item-title">' . $row['title'] . '</h5>';
                    
                    echo '<p class="cart-item-price">Price: ' . format_num($row['selling_price'], 2) . '</p>';
                    echo '<p class="cart-item-quantity">Quantity: ' . $row['quantity'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                }
                echo '</div>'; 
                
                echo '<div class="total-amount">Total Amount: ' . format_num($total_amount, 2) . '</div>';
                echo '<form action="?page=checkout&action=confirm" method="POST">';
                echo '<button type="submit" class="checkout-button">Confirm and Pay</button>';
                echo '</form>';
            } else {
                echo '<p class="text-center">Your cart is empty.</p>';
                echo '<a href="?page=products.php" class="go-back-button">Go Back to Gallery</a>';
            }
            ?>
        </div>
    </body>
    </html>

    <?php
}

function confirm_payment($conn) {
    $ip = getIPAddress();
    

    $delete_cart_query = "DELETE FROM cart_details WHERE ip_address='$ip'";
    $result = mysqli_query($conn, $delete_cart_query);
    if (!$result) {
        die('Query Failed: ' . mysqli_error($conn));
    } else {
        echo "<script>alert('Payment successful. Thank you for your purchase!');</script>";
        echo "<script>window.open('?page=products','_self');</script>";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'confirm') {
    $dbConnection = new DBConnection();
    $conn = $dbConnection->conn; 
    confirm_payment($conn);
} else {
    $dbConnection = new DBConnection();
    $conn = $dbConnection->conn; 
    checkout($conn);
}
?>