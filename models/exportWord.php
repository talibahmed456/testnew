<?php
$output="
First name: Nenad
Last name: Jevtic
Index: 60/20
Biography:

Hi, my name is Nenad Jevtić, and I am a desktop and web developer. I was lucky enough to work for several fun, exciting and successful StartUps that helped me to become who I am now. I am always thinking about speed, performance optimisation, scalability and the way to improve workflow for each individual project. Communication with me is simple, you are free to call me anytime you want. I keep up to date with the latest technologies and consider myself a very quick study able to pick up another technology in a very short amount of time as needed.";

    header("Content-Type: application/vnd.msword");
    header("Content-Disposition: attachment; filename=author.doc");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    header("Pragma: no-cache");

    echo $output;
?>