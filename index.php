<?php
    include "config/connection.php";
    include "models/functions.php";
    include "views/fixed/head.php";
    include "views/fixed/header.php";
    
    $line = '';
    $file=fopen('data/log.txt',"a+");
    $cursor = -1;

    fseek($file, $cursor, SEEK_END);
    $char = fgetc($file);

    /**
     * Trim trailing newline chars of the file
     */
    while ($char === "\n" || $char === "\r") {
        fseek($file, $cursor--, SEEK_END);
        $char = fgetc($file);
    }

    /**
     * Read until the start of file or first newline char
     */
    while ($char !== false && $char !== "\n" && $char !== "\r") {
        /**
         * Prepend the new char
         */
        $line = $char . $line;
        fseek($file, $cursor--, SEEK_END);
        $char = fgetc($file);
    }

    /* Log.txt format Y-m-d H:i:s - page  $date=substr($line,0,19)*/
    $date=substr($line,0,19);
    $date=new DateTime($date);
    $now=new DateTime('now');
    $dteDiff  = $date->diff($now);
    $dteDiff=$dteDiff->format("%a %H:%i:%s");
    if($dteDiff>"1 00:0:00"){
        fclose($file);
        unlink('data/log.txt');
        $file=fopen('data/log.txt',"a+");
    }
    if(isset($_GET['pages'])){
        $page=$_GET['pages'];
        switch($page){
            case 'login' : include 'views/user/login.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Login".PHP_EOL);
            break;
            case 'register' : include 'views/user/register.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Register".PHP_EOL);
            break;
            case 'cart' : include 'views/user/cart.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Cart".PHP_EOL);
            break;
            case 'checkout' : include 'views/user/checkout.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Checkout".PHP_EOL);
            break;
            case 'contact' : include 'views/user/contact.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Contact".PHP_EOL);
            break;
            case 'shop' : include 'views/user/shop.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Shop".PHP_EOL);
            break;
            case 'single-product' : include 'views/user/single-product.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Single product".PHP_EOL);
            break;
            case 'verification' : include 'views/user/verification.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Verification".PHP_EOL);
            break;
            case 'admin/addProduct' : include 'views/admin/addProduct.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Add product".PHP_EOL);
            break;
            case 'admin/addSurvey' : include 'views/admin/addSurvey.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Add survey".PHP_EOL);
            break;
            case 'admin/addUser' : include 'views/admin/addUser.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Add user".PHP_EOL);
            break;
            case 'adminpnl' : include 'views/admin/adminpnl.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Admin panel".PHP_EOL);
            break;
            case 'admin/answer-message' : include 'views/admin/answer-message.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Answer message".PHP_EOL);
            break;
            case 'admin/updateProduct' : include 'views/admin/updateProduct.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Update product".PHP_EOL);
            break;
            case 'admin/updateSurvey' : include 'views/admin/updateSurvey.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Update survey".PHP_EOL);
            break;
            case 'admin/updateUser' : include 'views/admin/updateUser.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Update user".PHP_EOL);
            break;
            case 'admin/view-order' : include 'views/admin/view-order-stats.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t View order stats".PHP_EOL);
            break;
            case 'admin/view-stats' : include 'views/admin/view-stats.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t View poll stats".PHP_EOL);
            break;
            case 'admin/LogFile' : include 'views/admin/logFile.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Website log file".PHP_EOL);
            break;
            case 'author' : include 'views/user/author.php';
            fwrite($file,$now->format("d-m-Y H:i:s")." \t Author".PHP_EOL);
            break;
            default:{
                fwrite($file,$now->format("d-m-Y H:i:s")." \t Index".PHP_EOL);
                include 'views/main.php';
                if($_SESSION){
                    if($_SESSION["role"]){
                        include "views/survey.php";
                    }
                }
            } 
        }
    }else{
        $file=fopen('data/log.txt',"a+");
        fwrite($file,$now->format("d-m-Y H:i:s")." \t Index".PHP_EOL);
        include 'views/main.php';
        if($_SESSION){
            if($_SESSION["role"]){
                include "views/survey.php";
            }
        }
    }
    fclose($file);
    include "views/fixed/footer.php";
?>