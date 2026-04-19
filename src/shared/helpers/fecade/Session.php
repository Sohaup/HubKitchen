<?php
namespace PostApi\shared\helpers\fecade;

class Session {
    public static function startSession() {
        session_start();
    }    
    public static function setSession(string $key , mixed $value) {        
        $_SESSION[$key] = $value;
    }
    public static function getSession(string $key) {
        return $_SESSION[$key];
    }
    public static function deleteSession(string $key) {
        unset($_SESSION[$key]);
    }
    public static function closeSession() {
        session_destroy();
    }
}