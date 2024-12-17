<?php

namespace MVC\core;

class app
{
  private $controller;
  private $method;
  private $params;
  public function __construct()
  {
    $this->url();
    $this->render();
  }
  private function url()
  {
    if (!empty($_SERVER['QUERY_STRING'])) {
      $url = explode('/', $_SERVER['QUERY_STRING']);
      $this->controller = !empty($url[0]) ? $url[0] . 'controller' : 'index';
      $this->method = !empty($url[1]) ? $url[1] : 'index';
      $position = strpos($this->method, '&');
      if ($position !== false) {
          $this->method = substr($this->method, 0, $position);
      }
      unset($url[0], $url[1]);
      $this->params = array_values($url);
    } else {
      echo 'EMPTY QUERY STRING';
    }
  }
  private function render()
  {
    $controller = 'MVC\controllers\\' . $this->controller;
    if (class_exists($controller)) {
      if (method_exists($controller, $this->method)) {
        $controller = new $controller;
        call_user_func_array([$controller, $this->method], $this->params);
      } else {
        echo 'METHOD NOT EXISTS ' . $this->method;
      }
    } else {
      echo 'CLASS NOT EXISTS';
    }
  }
}
