<?php
    DEFINE("SERVER",env("SERVER"));
    DEFINE("DBNAME",env("DBNAME"));
    DEFINE("USERNAME",env("USERNAME"));
    DEFINE("PASSWORD",env("PASSWORD"));

    function env($marker){
        $podaci = file(__DIR__."/.env");
        $r = "";
        foreach($podaci as $p){
            $delovi = explode("=", $p);
            if($delovi[0] == $marker){
                $r = $delovi[1];
                break;
            }
        }
        return trim($r);
    }
?>