<?php

include("./Tools/view.class.php");
include("./Tools/traffic.class.php");

$page = new View();
$traffic = new Traffic();

session_start();
define('HOST', 'http://territorialcup.azurewebsites.net');

if($traffic->check_ajax()){
    $params = split("/", $_SERVER['REQUEST_URI']);
    switch ($params[1]) {
        case "user":
            include("user.controller.php");
            $game = new UserController($params);
            break;
        case "game":
        default:
            include("game.controller.php");
            $game = new GameController($params);
            break;
    }
} else {
    if(isset($_SESSION['username']))
        $page->vars['login'] = true;
    else
        $page->vars['login'] = false;
    $page->render("single_page.view.php");
}
?>
