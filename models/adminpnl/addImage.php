<?php
header("Content-type: application/json");

if($_SERVER["REQUEST_METHOD"]=="POST"){
    include "../../config/connection.php";
    include "../functions.php";

    try{
        session_start();
        $id=$_SESSION['last_index'];
        $slika=$_FILES['slika'];
        $dozvoljeniTipoviSlika = ["image/jpg", "image/jpeg", "image/png"];
        $slikaIme = $slika['name'];
        $slikaTmpFajla = $slika['tmp_name'];
        $slikaVelicina = $slika['size'];
        if($slikaVelicina<2500000){
            $slikaTipFajla = $slika['type'];
            if (in_array($slikaTipFajla, $dozvoljeniTipoviSlika)){
                if($slikaTipFajla=="image/jpg"){
                    $source_image = imagecreatefromjpeg($slikaTmpFajla);
                }else if($slikaTipFajla=="image/jpeg"){
                    $source_image= imagecreatefromjpeg($slikaTmpFajla);
                }else if($slikaTipFajla=="image/png"){
                    $source_image=imagecreatefrompng($slikaTmpFajla);
                }
                $width = imagesx($source_image);
                $height = imagesy($source_image);
                $thumbnail=imagecreatetruecolor($width,$height);
                imagecopy($thumbnail,$source_image,0,0,0,0,$width,$height);
                $novoImeSlike = time() . "_" . $slikaIme;
                $putanjaThumbnail="../../assets/img/imagesDB/Thumbnail/".$novoImeSlike;
                if($slikaTipFajla=="image/jpg"){
                    header('Content-Type: image/jpeg');
                    imagejpeg($thumbnail,$putanjaThumbnail.$novoImeSlike,50);
                    imagedestroy($thumbnail);
                }else if($slikaTipFajla=="image/jpeg"){
                    header('Content-Type: image/jpeg');
                    imagejpeg($thumbnail,$putanjaThumbnail.$novoImeSlike,50);
                    imagedestroy($thumbnail);
                }else if($slikaTipFajla=="image/png"){
                    header('Content-Type: image/png');
                    imagepng($thumbnail,$putanjaThumbnail,9);
                    imagedestroy($thumbnail);
                }
                $putanja = "../../assets/img/imagesDB/" . $novoImeSlike;
                $putanjaThumbnailAdd=substr($putanjaThumbnail,13);
                $putanjaAdd=substr($putanja,13);
                if (move_uploaded_file($slikaTmpFajla, $putanja)) {
                    $upitInsertSlike = $connection->prepare("INSERT INTO pictures (src, alt,src_thb, product_id) VALUES (:src, :alt,:src_thb, :proizvod_id)");
                    $upitInsertSlike->bindParam(":proizvod_id", $id);
                    $upitInsertSlike->bindParam(":src", $putanjaAdd);
                    $upitInsertSlike->bindParam(":src_thb", $putanjaThumbnailAdd);
                    $upitInsertSlike->bindParam(":alt", $slikaIme);
                    $upitInsertSlike->execute();

                    if ($upitInsertSlike) {
                        http_response_code(201);
                    }
                }
            }
        }
        
    }catch(PDOException $e){
        http_response_code(500);
    }
}


?>