<?php
    //Inicializar la sesión
    session_start();
    
    //Destruir todas las variables de sesión
    $_SESSION = array();
    
    //Es necesario destruir la cookie para no dejar rastro de la sesión
    if (ini_get("session.use_cookies")) 
    {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    }
    //Destruir la sesión y redirigir al index
    session_destroy();
    header('location:../index.php');
    exit();
?>