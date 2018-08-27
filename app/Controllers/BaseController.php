<?php
/*  
    This controller is an abstract class. It will not be used on it's own.
    It serves as a base to all other controllers.
    It's main purpose is to store methods common to all controllers, 
    so we don't have to define them every time in every controller. 

    In this small app it does just one thing - attaches app's container to the controller,
    So we can use the Twitter service and View engine in HomeController.
*/
namespace App\Controllers;

use Interop\Container\ContainerInterface;

abstract class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
}