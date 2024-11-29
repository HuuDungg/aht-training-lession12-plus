<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("./controller/BlogController.php");


$blogController = new BlogController();
$act = $_GET["act"] ?? "/";

switch ($act) {
    case "/":
        include("./view/product/list.php");
        break;
    case "list":
        $blogController->display();
        break;
    case "add":
        $blogController->create();
        break;
    case "delete":
        $blogController->delete();
        break;
    case "edit":
        $blogController->getById();
        break;
    case "save":
        $blogController->update();
        break;
    default:
        echo "404 - Page not found";
}
