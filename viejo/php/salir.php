<?php
    //Inicializar la sesi�n
    session_start();
    
    //Destruir todas las variables de sesi�n
    $_SESSION = array();
    
    //Es necesario destruir la cookie para no dejar rastro de la sesi�n
    if (ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    //Destruir la sesi�n y redirigir al index
    session_destroy();
    header('location:../index.php');
    exit();
?>