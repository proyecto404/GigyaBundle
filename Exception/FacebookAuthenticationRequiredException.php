<?php

namespace MyNeeds\FacebookBundle\Exception;

class FacebookAuthenticationRequiredException extends \RuntimeException
{
    public function __construct($message = 'Facebook Authentication Required', \Exception $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
