<?php

require_once('./vendor/firebase/php-jwt/src/JWT.php');
require_once('./vendor/firebase/php-jwt/src/ExpiredException.php');

use Firebase\JWT\JWT as JWT;
use Firebase\JWT\ExpiredException as ExpiredException;

class Util{

    public static function getSecretkey(){
        return "fundmus_key";
    }

    public static function VerifyToken(){
        $key = Util::getSecretkey();
        $token = apache_request_headers()['Authorization'];
        try {
            return JWT::decode($token, $key, array('HS256'));            
        } catch (ExpiredException $e) {
            return false;
        } catch (\Exception $e){
            return false;
        }
    }
}

?>