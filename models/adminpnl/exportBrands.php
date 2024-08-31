<?php
include "../../config/connection.php";

session_start();
$access_method = $_SERVER["REQUEST_METHOD"];
    if($access_method !== 'GET' || empty($_SESSION['username']) || $_SESSION['role'] != 1)
    {
        header("Location"."index.php");
        exit();
    }

    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Type: application/x-msexcel");
    header("Content-Disposition: attachment; filename=brands.xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    header("Pragma: no-cache");

    $query="SELECT * FROM brand";
    $prepare=$connection->prepare($query);
    $prepare->execute();
    $result=$prepare->fetchAll();
    $output = "Brand Id\tBrand Name\n";
    foreach($result as $brand){
        $output.=$brand->brand_id . "\t" . $brand->brand_name ."\n";
    }

    echo $output;
?>