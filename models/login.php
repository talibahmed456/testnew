<?php
        header("Content-type: application/json");
        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\SMTP;
        use PHPMailer\PHPMailer\Exception;
        require '../assets/phpMailer/vendor/autoload.php';
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        include "../config/connection.php";
        include "functions.php";
        try{
            $username=$_POST["username"];
            $password=$_POST["password"];
            
            
            $paternUsername="/^(?=.{8,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+$/";
            //$paternPassword="/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[a-zA-Z]).{8,}$/";
            $paternPassword="/^(?=.*[a-z])(?=.*[A-Z]).{8,32}$/";
            $statusMessage="";
            if(preg_match($paternUsername,$username)  && preg_match($paternPassword,$password)){
                $status=checkAccount($username);

                if($status){
                    $login= login($username, $password);
                    if(!$login){
                        $statusMessage="<p style='color: red'>Invalid password</p>";
                        $loginAttempts=fetchAttempts($username);
                        /* if(isset($_SESSION['loginAttempt'])){
                            $_SESSION['loginAttempt']=$_SESSION['loginAttempt']+1;
                        }else{
                            $_SESSION['loginAttempt'] = 0;
                        } */
                        if($loginAttempts==0){
                            attemptStart($username);
                        }
                        $firstAttempt=new DateTime(attemptDate($username));
                        $now=new DateTime('now');
                        $dteDiff  = $firstAttempt->diff($now);
                        if(($dteDiff->format("%H:%I:%S"))<"00:05:00"){
                            $loginAttempts++;
                            updateAttempts($username,$loginAttempts);
                        }else{
                            //resetTime($username);
                            updateAttempts($username,0);
                        }
                        if(fetchAttempts($username)==2){
                            LockAccount($username);
                            $statusMessage="<h6 style='color: red; font-size: 16px'>Account with username ".$username." is locked. Contact administrator</h6>";
                            $email=getEmail($username);
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
                            $mail->addAddress($email);


                            //Content
                            $mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = 'Account warning';
                            $mail->Body    = "<h4>Ms/Mrs. ".$username.",</h4><h5> we inform you that your account is locked, because of multiple failed login attempts.</hh5></br>
                                                <h6>Please, contact administrator for further informations.</h6></br></br></br><b><h5>Flex shop admin team</h5></b>";

                            $mail->send();
                        }
                    }else{
                        $locked=isLocked($username);

                        if($locked){
                            $statusMessage="<h6 style='color: red'>Account with username ".$username." is locked. Contact administrator</h6>";
                            session_destroy();
                            
                        }else{
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
                            $temp=0;
                            if($file){
                                while(($line=fgets($file))!==false){
                                    $parts=explode("\t",$line);
                                    if($username==trim($parts[1])){
                                        $temp=1;
                                    }
                                }
                            }
                            if($temp!=1){
                                fwrite($file,$date->format("d-m-Y H:i:s")."\t".$username.PHP_EOL);
                            }
                            fclose($file);

                            $verified=isVerified($username);
                            updateAttempts($username,0);
                            if($verified){
                                $statusMessage="<h6 style='color: #2aa32c'>Welcome, ".$username."</h6>";
                            }else{
                            $statusMessage="<h4 style='color: #2aa32c'>Verification needed, ".$username."</h4>";
                            $email=getEmail($username);
                            $vNumber=getvNumber($username);
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
                            $mail->addAddress($email);


                            //Content
                            $mail->isHTML(true);                                  //Set email format to HTML
                            $mail->Subject = 'Verification code';
                            $mail->Body    = "<h5>This is your verification number:</h5><h1><b>$vNumber</b></h1>";
                            $mail->AltBody = $vNumber;

                            $mail->send();
                        }
                        $_SESSION['username']=$username;
                        }
                    }
                }else{
                    $statusMessage="<p style='color: red'>Username does not exist</p>";
                }
            }

            $answer = ["answer"=>$statusMessage];
            echo json_encode($answer);
            http_response_code(201);
            
        }catch(PDOException $e){
            var_dump($e->getMessage());
            http_response_code(500);
        }

      }


?>