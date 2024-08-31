<?php
header("Content-type: application/json");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include "../config/connection.php";
    include "functions.php";

    try{
        $vNumber=$_POST['code'];
        session_start();

        $username=$_SESSION['username'];

        $query='SELECT validation_code FROM user WHERE username=:username';
        $statusMessage="";
        $prepare=$connection->prepare($query);
        $prepare->bindParam(":username",$username);
        $prepare->execute();

        $result=$prepare->fetch();

        if($result->validation_code==$vNumber){
            if(setVerified($username)){
                $statusMessage.="<h6 style='color: green'>Verification completed.</h6>";
                $statusMessage.="<h6 style='color: green'>Redirecting..</h6>";
                    $file=fopen('../data/user.txt',"a+");
                    $firstLine=fgets($file);
                    fclose($file);
                    $file=fopen('../data/user.txt',"a+");
                    $firstLine=explode("\t",$firstLine);
                    $firstDate=$firstLine[0];
                    $firstDate=new DateTime($firstDate);
                    $date=new DateTime('now');
                    $dteDiff  = $firstDate->diff($date);
                    $dteDiff=$dteDiff->format("%a %H:%i:%s");
                    if($dteDiff>"1 00:0:00"){
                        fclose($file);
                        unlink('../data/user.txt');
                        $file=fopen('../data/user.txt',"a+");
                    }
                    fwrite($file,$date->format("d-m-Y H:i:s")."\t".$username.PHP_EOL);
                    fclose($file);
            }
        }else{
            $statusMessage="<p style='color: red'>Invalid code.</p>";
        }
        $answer = ["answer"=>$statusMessage];
        echo json_encode($answer);
    }catch(PDOException $e){
        http_response_code(500);
    }

}
?>