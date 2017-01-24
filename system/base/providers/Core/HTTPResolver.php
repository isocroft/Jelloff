<?php

namespace Providers\Core;

use \Router;
use \System;
use \Request;
use \Response;
use \Auth;

class HTTPResolver{

	 protected $currentController;

	 private  $resolverMethod;

	 public function __construct(){

	 	 $this->currentController = NULL;

         $this->resolverMethod = '';

	 }

	 public function draftRouteHandler($method){

	 	  $this->resolverMethod = $method;

 	 }

 	 private function getResolverURIParts($url){

         $pathname = preg_replace('/^\/|\/$/', '', $url);

 	 	 return (explode('/', $pathname));

 	 }

 	 public function getResolverMethod(){

         return $this->resolverMethod;

 	 }

     public function handleCurrentRoute(Router $router, System $sys, Auth $auth){

            $uri = Request::uri();

            if(preg_match('/\.(?:[a-z]{1,5})$/i', $uri)){
                    if(file_exists(realpath($uri))){
                          return $router->fromDisk($uri);
                    }
            }

            if(!$router->findRoute($uri)){
                if($sys->hasBlindRouteCallback()){
                   $sys->fireCallback('BLIND_ROUTE_CALLBACK', array($uri));
                }else{

                   throw new \Exception("Route Not Found >> ['" . $uri . "'] ");
                }
            }

            $method = $this->getResolverMethod();
            $uriParts = $this->getResolverURIParts($router->getCurrentRouteUrl());

            $models = $router->getRouteSettings($method, $sys, $auth);

            $GLOBALS['app']->cacheModelInstances($models);

            $controllerClass = '\\';
            $controllerMethod = (array_key_exists(1, $uriParts) && index_of($uriParts[1], '@') != 0)? $uriParts[1] : 'index';

            $controllerClass .=  (array_key_exists(0, $uriParts) && strlen($uriParts[0]))? ucfirst($uriParts[0]) : 'Controller';


            if(class_exists($controllerClass)){
                 $this->currentController = new $controllerClass($router->getCurrentRouteParameters());
                 $meth = preg_replace('/\-/', '_', $controllerMethod);
                 // TODO: Later, we could do dependency injection to controller methods for Model class names via args list here...
                 if(method_exists($this->currentController, $meth)){
                     return $this->currentController->{$meth}($models);
                 }
            }else{
                 throw new \Exception("Controller Not Found >> ['". $controllerClass . "'] ");
            }

            return NULL;
     }
}


?>