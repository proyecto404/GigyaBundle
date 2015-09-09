<?php

namespace MyNeeds\FacebookBundle\Exception;

class FacebookTokenExpiredException extends \RuntimeException
{
    public function __construct($message = 'Facebook Token Expired', \Exception $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
