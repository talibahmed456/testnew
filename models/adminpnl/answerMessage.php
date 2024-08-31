<?php
    header("Content-type: application/json");
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    require '../../phpMailer/vendor/autoload.php';
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        include "../../config/connection.php";
        include "../functions.php";

        try{
            $id=$_POST['id'];
            $answer=$_POST['answer'];
            $statusMessage="";
            $query="UPDATE admin_message SET answer=:answer, answered=1 WHERE message_id=:id";
            $prepare=$connection->prepare($query);
            $prepare->bindParam(":answer",$answer);
            $prepare->bindParam(":id",$id);
            $result=$prepare->execute();

            if($result){
                $query="SELECT email_user, name, message FROM admin_message WHERE message_id=:id";
                $prepare=$connection->prepare($query);
                $prepare->bindParam(":id",$id);
                $prepare->execute();
                $result=$prepare->fetch();
                $statusMessage="<span class='fw-bold' style='color:#2aa32c; font-size:18px'>Answer sent!</span>";
                $mail = new PHPMailer(true);

                    //Server settings
                        $mail->isSMTP();                                            //Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
                        $mail->Username   = 'emailzaphp69@gmail.com';                     //SMTP username
                        $mail->Password   = 'Idegas123';                               //SMTP password
                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
                        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //Recipients
                        $mail->setFrom('emailzaphp69@gmail.com', 'Flex shop');
                        $mail->addAddress($result->email_user);


                        //Content
                        $mail->isHTML(true);                                  //Set email format to HTML
                        $mail->Subject = 'Message answer';
                        $mail->Body    = "<h5>Hi $result->name,</h5></br>
                                        <p>Message: $result->message</p></br>
                                        <p>Answer to your message: $answer</p></br></br><h6>Flex shop admin team</h6>";
                        $mail->AltBody = "Message answer";

                        $mail->send();
            }else{
                $statusMessage="<p style='color: red'>An error occured.</p>";
            }

                        $answer = ["answer"=>$statusMessage];
                        echo json_encode($answer);
                        http_response_code(201);

        }catch(PDOException $e){
            http_response_code(500);
        }
    }


?>