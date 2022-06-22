<?php
include_once "../vendor/autoload.php";
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$request= Request::createFromGlobals();
$response= new Response();

switch ($request->getPathInfo()) {
    case '/front-controller/page1.php':
     
        ob_start();
        include __DIR__.'/page1.php';
        $response->setContent(ob_get_clean());
        break;
    case '/front-controller/page2.php':
        ob_start();
        include __DIR__.'/page2.php';
        $response->setContent(ob_get_clean());
        break;
    default:
      $response->setContent("page not found");
      $response->setStatusCode(404);
        break;
    
}
$response->send();