<?php

if(isset($_GET["optoken"])){
    $conn = new mysqli("sql306.infinityfree.com","if0_38006473","eKV6FOw7dtXlQFY","if0_38006473_database");
    if($conn->connect_error){
          http_response_code(500);
         echo "error 500: database related error.";
         exit;
     }
    $result = $conn -> query("SELECT result FROM tokenentry WHERE token ='" . $_GET["optoken"] . "'");
    
    if($result && $result->num_rows > 0){
        $conn -> query("UPDATE tokenentry 
                        SET result = 'green' 
                        WHERE token ='" . $_GET["optoken"] . "'");
        $conn -> close();
        echo "verification done. green assigned.";
        exit;


    }else{
        echo "token doesnt exist in database.";
        $conn -> close();
        exit;

    }
}else{
    echo "no token.";
    exit;
}




?>