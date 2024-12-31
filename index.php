<?php
session_start();
include 'phpqrcode.php';

if(!isset($_GET["cause"])){
    http_response_code(400);
    echo "error 400: cause not included.";
    exit;
}

if($_GET["cause"]=="start"){
    $token = bin2hex(random_bytes(32));
    $conn = new mysqli();
    if($conn->connect_error){
        http_response_code(500);
        echo "error 500: database related error.";
        exit;
    }
    $conn -> query("INSERT INTO tokenentry (token) VALUES('" . $token . "')");
    $conn->close();
    $_SESSION["optoken"] = $token;
    QRcode::png("http://qr-api.ct.ws/verification.php?optoken=" . $token);
    
}
else if($_GET["cause"]=="try"){
    if(isset($_SESSION["optoken"])){
        $conn = new mysqli("");
        if($conn->connect_error){
            http_response_code(500);
            echo "error 500: database related error.";
            exit;
        }
        $result = $conn -> query("SELECT result FROM tokenentry WHERE token ='" . $_SESSION['optoken'] . "'");
        $resultdata = $result -> fetch_assoc();

        if($resultdata["result"]==="green"){
            http_response_code(200);
            echo "verification is done succesfully.";
            $conn -> query("DELETE FROM tokenentry WHERE token ='" . $_SESSION['optoken'] . "'");
            $conn -> close();
            session_unset();
            session_destroy();
            exit;
        }else if($resultdata["result"]==="red"){
            http_response_code(400);
            echo "error 400: verification rejected.";
            session_unset();
            session_destroy();
            $conn -> close();
            exit;
        }else{
            http_response_code(202);
            echo "awaiting verification action..";
            $conn -> close();
            exit;
        }
        
    }else{
        http_response_code(400);
        echo "error 400: start required before try.";
        exit;
            
        }
}else{
    http_response_code(400);
    echo "error 400: cause not valid.";
    exit;
}


?>
