<?php

namespace Lib\Exceptions;

class RouteNotFoundException extends \Exception
{
    protected $message = "Cette route n'existe pas.";
}
