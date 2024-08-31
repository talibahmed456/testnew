<?php
    include "config/connection.php";
    include "models/functions.php";
    include "pages/head.php";
    include "pages/header.php";
?>

<?php

  if($_SESSION){
    if($_SESSION["role"]==1){
        $id=$_GET['id'];
        $message=fetchMessage($id);
        echo "<section class='section-margin--small mb-5'>
        <div class='container d-flex justify-content-center mt-5'>
            <div class='col-12 col-md-9 col-lg-6 mt-5 d-flex flex-column align-items-center'>
                            <h3 class='center mb-5'>Answer message</h3>
                            <form class='row login_form' action='#/' id='register_form' >
                                    <div class='col-md-12 form-group'>
                                        <p>Full name: ".$message->name."</p>  
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <p>Email: ".$message->email_user."</p>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <p>Date: ".$message->date."</p>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <p>Subject: ".$message->subject."</p>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <p>Message: ".$message->message."</p>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <textarea class='form-control different-control w-100 textareaHeight' name='message' id='messageAnswer' cols='30' maxlength='300' placeholder='Enter Answer'></textarea>
                                        <span class='greska hidden' id='upozorenjeText'>Enter Amswer!</span>
                                    </div>
                                    <div class='col-md-12 form-group'>
                                        <button type='button' value='Answer message' id='btnAnswerMessage' class='button button-register w-100'>Answer</button>
                                    </div>
                                        <div class='col-md-12 form-group d-flex justify-content-center' id='statusAnswer'>
                                    </div>
                            </form>
                    </div>
                </div>
    </section>";
    }
  }

?>


<?php
	include "pages/footer.php";
?>