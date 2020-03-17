<?php


class Session {

    public static function create($params){

        session_start();

        foreach($params as $key => $value){
            $_SESSION[$key] = $value;
        }
    }

    public static function start(){

        session_start();
        return $_SESSION;
    }


    public static function destroy(){

        session_start();
        session_destroy();
    }

    public static function authenticate($params){

        foreach($params as $key){

            if(!isset($_SESSION[$key])){
                return false;
            }
        }

        return true;
    }
}