<?php

namespace MVC\core;

class controller
{
  public static function view($path, $params = [])
  {
    extract($params);
    require_once(VIEWS . $path . '.php');
  }
}
