<?php
include('config/dbdata.php');
include('config/connection.php');

require_once('./vendor/firebase/php-jwt/src/JWT.php');
require_once('./Util.php');

use Firebase\JWT\JWT as JWT;


$dbConn = connect($db);

class Auth{

    public function login($dbConn){

        $rut = $_POST['rut'];
        $pass = $_POST['pass'];
        
        $sql = $dbConn->prepare("SELECT * FROM usuarios where userrut='".$rut."' and userpassword='".$pass."'");
        $sql->execute();
        $sql->setFetchMode(PDO::FETCH_ASSOC);
        $res = $sql->fetchAll();
        if($res != ''){
            $key = Util::getSecretkey();
            $time = time();
            $payload = [
                "iat" => $time,
                "exp" => $time + 60*10,
                "data" => ['rut' => $rut]
            ];

            $jwt = JWT::encode($payload,$key);

            echo json_encode(['token' => $jwt]);
        }else{
            echo json_encode(['token' => 'NULL']);
        }
    }
}
$auth = new Auth();
$auth->login($dbConn);
?>