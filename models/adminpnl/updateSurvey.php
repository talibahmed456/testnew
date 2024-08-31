<?php
    header("Content-type: application/json");

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        include "../../config/connection.php";
        include "../functions.php";

        try{
            $id=$_POST["id"];
            $name=$_POST["name"];
            $status=$_POST["status"];
            $statusMessage="";

            if(trim($name)!=""){

                $surveyExist=surveyExist($name);
                if($surveyExist){
                    $updateSurvey=updateSurvey($id,$name, $status);
    
                    if($updateSurvey){
                        $statusMessage="<span class='fw-bold' style='color:#2aa32c; font-size:18px'>Survey updated!</span>";
                    }else{
                        $statusMessage="<p style='color: red'>An error occured.</p>";
                    }
                }else{
                    $statusMessage="<p style='color: red'>Survey name does not exist.</p>";
                }
            }
            $answer = ["answer"=>$statusMessage];
            echo json_encode($answer);
            http_response_code(201);

        }catch(PDOException $e){
            http_response_code(500);
        }

    }

?>