<?php
    if($_SESSION){
        if($_SESSION["role"]==1){
                echo "<section class='section-margin--small mb-5'>
                <h1 class='text-center pt-5'>Website log</h1></br>
                <div class='d-flex justify-content-center'>
                    <table class='w-75 table table-striped table-responsive-md'>
                      <thead>
                        <th>Date and time</th>
                        <th>Page</th>
                      </thead>
                      <tbody>";
                      echo displayLogFile();  
                      echo "</tbody>
                    </table>
                </div>
                <div class='d-flex justify-content-center'>
                    <input type='button' class='btn btn-primary mx-2' value='Back' onclick='redirectAdmin()'/>
                    <a class='btn btn-primary' href='models/adminpnl/clearLog.php'>Clear log</a>
                </div>
              </section></br>";
        }else{
              echo "<section class='section-margin--small mb-5'><div class='container mt-5'>
              <div class='d-flex justify-content-center mt-5'>
              <h1 class='mt-5'>Restricted area</h1>
              </div>
              </div></section>";
              }
    }else{
            echo "<section class='section-margin--small mb-5'><div class='container mt-5'>
            <div class='d-flex justify-content-center mt-5'>
            <h1 class='mt-5'>Restricted area</h1>
            </div>
            </div></section>";
    }
?>