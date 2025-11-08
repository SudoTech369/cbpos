<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once('../../config.php'); // adjust based on actual location

$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
$data = [];

if($product_id > 0){
    $qry = $conn->query("SELECT id, name FROM sub_products WHERE product_id = '{$product_id}' AND delete_flag = 0 ORDER BY name ASC");
    if($qry){
        while($row = $qry->fetch_assoc()){
            $data[] = $row;
        }
    }
}

header('Content-Type: application/json');
echo json_encode($data);
exit;
