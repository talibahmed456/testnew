<?php
    header("Content-type:application/json");
    include "../../config/connection.php";
    if($_SERVER["REQUEST_METHOD"]=="POST"){
    try{

        $query="SELECT p.product_id, p.product_name, i.src_thb, i.alt, p.newPrice FROM product p INNER JOIN pictures i ON p.product_id=i.product_id WHERE p.sale=1";

        $prepare=$connection->prepare($query);
        $prepare->execute();

        $result=$prepare->fetchAll();
        $temp=0;
        $output="";
        for($i=0;$i<3;$i++){
            $br=0;
            $output.="<div class='col-sm-12 col-xl-4 mb-5 mb-xl-0'><div class='single-search-product-wrapper'>";
            for($j=$temp;$j<count($result);$j++){
                $output.="<div class='single-search-product d-flex'>
                <a href='index.php?pages=single-product&id=".$result[$j]->product_id."'><img class='image-fluid' src='assets/".$result[$j]->src_thb."' alt='".$result[$j]->alt."'></a>
                <div class='desc'>
                    <a href='index.php?pages=single-product&id=".$result[$j]->product_id."' class='title'>".$result[$j]->product_name."</a></br>
                    <div class='price'>".$result[$j]->newPrice."$</div>
                </div>
                </div>";
                $br=$br+1;
                
                if($br==3){
                    $temp=$temp+3;
                    break;
                }
            }

            $output.="</div></div>";
        }

        $answer=["answer"=>$output];
        echo json_encode($answer);
        http_response_code(201);
        

    }catch(PDOException $e){
        http_response_code(500);
    }
}
?>