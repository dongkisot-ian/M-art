<style>
    img.img-thumbnail.product-thumb {
        width: 5rem;
        height: 5rem;
        object-fit: scale-down;
        object-position: center center;
    }
    #product-list .prod-item:nth-child(even){
        direction:rtl !important;
    }
    #product-list .prod-item:nth-child(even) > * {
        direction:ltr !important;
    }
</style>

<?php
require_once('./functions/common_functions.php');
require_once('./classes/DBConnection.php');
$dbConnection = new DBConnection();
$conn = $dbConnection->conn;
//calling functions
getIPAddress();
//calling product cards
getproducts();
//calling cart
$dbConnection = new DBConnection();
cart($dbConnection->conn);
//  $ip = getIPAddress();  
//  echo 'User Real IP Address - '.$ip;



?>

<script>
    $(function(){
        
    })
</script>