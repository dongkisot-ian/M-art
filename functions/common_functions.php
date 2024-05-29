<?php
include_once('./classes/DBConnection.php');

function getproducts() {
    $dbConnection = new DBConnection();
    $conn = $dbConnection->conn; 

    ?>
    <section class="py-4">
        <div class="container">
            <h3 class="fw-bolder text-center">Available ArtWorks</h3>
            <center>
                <hr class="bg-primary w-25 opacity-100">
            </center>
            <div class="row" id="product-list">
                <?php
                $qry = $conn->query("SELECT p.*, c.name as category, u.username FROM `product_list` p 
                                     INNER JOIN `category_list` c ON p.category_id = c.id 
                                     INNER JOIN users u ON p.user_id = u.id 
                                     WHERE p.`status` = 1 AND p.`stock` > 0");
                while($row = $qry->fetch_assoc()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= validate_image(is_file(base_app."uploads/thumbnails/".($row['id']).".png") ? "uploads/thumbnails/".$row['id'].".png?v=".(strtotime($row['date_updated'])) : '') ?>" class="card-img-top product-thumb" alt="">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= $row['title'] ?></h5>
                            <p class="card-text truncate-3"><?= $row['short_description'] ?></p>
                            <div class="mb-2">
                                <div class="d-flex align-items-center mb-1">
                                    <i class="material-icons me-2">category</i>
                                    <span>Category: <?= isset($row['category']) ? $row['category'] : 0 ?></span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <i class="material-icons me-2">sell</i>
                                    <span>Price: <?= isset($row['selling_price']) ? format_num($row['selling_price'], 2) : 0 ?></span>
                                </div>
                            </div>
                            <div class="mt-auto text-center">
                                <a href="./?page=products/view_details&id=<?= $row['id'] ?>" class="btn btn-primary w-100 mb-2">View Details</a>
                                <a href="?page=products&action=add_to_cart&id=<?= $row['id'] ?>" class="btn btn-info w-100">Add to Cart</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php
}

function getIPAddress() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function cart($conn) {
    if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['id'])) {
        $ip = getIPAddress();
        $get_product_id = $_GET['id'];

        $check_product_query = "SELECT * FROM `product_list` WHERE id='$get_product_id' AND `status` = 1 AND `stock` > 0";
        $result_product_query = mysqli_query($conn, $check_product_query);
        if (!$result_product_query) {
            die('Product Query Failed: ' . mysqli_error($conn));
        }

        $num_of_rows = mysqli_num_rows($result_product_query);
        if ($num_of_rows > 0) {
            $select_query = "SELECT * FROM `cart_details` WHERE ip_address='$ip' AND product_id=$get_product_id";
            $result_query = mysqli_query($conn, $select_query);
            if (!$result_query) {
                die('Select Query Failed: ' . mysqli_error($conn));
            }

            $num_of_rows = mysqli_num_rows($result_query);
            if ($num_of_rows > 0) {
                echo "<script>alert('Product already in cart');</script>";
                echo "<script>window.open('?page=products','_self');</script>";
            } else {
                $update_product_query = "UPDATE `product_list` SET `stock` = `stock` - 1 WHERE id='$get_product_id'";
                $result_update_query = mysqli_query($conn, $update_product_query);
                if (!$result_update_query) {
                    die('Update Query Failed: ' . mysqli_error($conn));
                }

                $insert_query = "INSERT INTO `cart_details` (product_id, ip_address, quantity) VALUES ($get_product_id, '$ip', 1)";
                $result_insert = mysqli_query($conn, $insert_query);
                if (!$result_insert) {
                    die('Insert Query Failed: ' . mysqli_error($conn));
                }
                echo "<script>alert('Item added to cart');</script>";
                echo "<script>window.open('?page=products','_self');</script>";
            }
        } else {
            echo "<script>alert('Product is no longer available');</script>";
            echo "<script>window.open('?page=products','_self');</script>";
        }
    }
}

function remove_from_cart($conn, $product_id) {
    $ip = getIPAddress();
    $delete_query = "DELETE FROM cart_details WHERE ip_address='$ip' AND product_id='$product_id'";
    $result = mysqli_query($conn, $delete_query);
    if (!$result) {
        die('Query Failed: ' . mysqli_error($conn));
    } else {
        echo "<script>alert('Item removed from cart');</script>";
        echo "<script>window.open('?page=cart','_self');</script>";
    }
}

function display_cart($conn) {
    if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
        $product_id = $_GET['id'];
        remove_from_cart($conn, $product_id);
    }

    $ip = getIPAddress();
    $select_cart_items = "
        SELECT c.*, p.title, p.short_description, p.selling_price, p.date_updated
        FROM cart_details c
        JOIN product_list p ON c.product_id = p.id
        WHERE c.ip_address = '$ip'";

    $cart_items = mysqli_query($conn, $select_cart_items);

    if (!$cart_items) {
        die('Query Failed: ' . mysqli_error($conn));
    }
    
    $subtotal = 0; // Initialize subtotal
    if (mysqli_num_rows($cart_items) > 0) {
        echo '<div class="container">';
        echo '<h3 class="fw-bolder text-center">My Cart</h3>';
        echo '<div class="row">';
        while ($row = mysqli_fetch_assoc($cart_items)) {
            $image_url = validate_image(is_file(base_app . "uploads/thumbnails/" . ($row['product_id']) . ".png") ? "uploads/thumbnails/" . ($row['product_id']) . ".png?v=" . (strtotime($row['date_updated'])) : '');
            $subtotal += $row['selling_price'] * $row['quantity']; 
            echo '<div class="col-md-12 mb-4">';
            echo '<div class="card h-100">';
            echo '<div class="row">';
            echo '<div class="col-3 text-center">';
            echo '<img src="' . $image_url . '" class="product-thumb" alt="">';
            echo '</div>';
            echo '<div class="col-9">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $row['title'] . '</h5>';
            echo '<p class="card-text">' . $row['short_description'] . '</p>';
            echo '<p class="card-text">Price: ' . format_num($row['selling_price'], 2) . '</p>';
            echo '<p class="card-text">Quantity: ' . $row['quantity'] . '</p>';
            echo '<a href="?page=cart&action=remove&id=' . $row['product_id'] . '" class="btn btn-info">Remove from Cart</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>'; 
            echo '</div>'; 
            echo '</div>'; 
        }
        echo '</div>'; // End 
        echo '<div class="row">';
        echo '<div class="col-md-12 text-end">';
        echo '<h4>Subtotal: ' . format_num($subtotal, 2) . '</h4>';
        echo '<a href="checkout.php" class="btn btn-success">Checkout</a>';
        echo '</div>';
        echo '</div>'; 
        echo '</div>'; // End 
    } else {
        echo '<div class="container">';
        echo '<h3 class="fw-bolder text-center">My Cart</h3>';
        echo '<p class="text-center">Your cart is empty.</p>';
        echo '</div>';
    }
}




?>

