<?php

namespace Proyecto404\GigyaBundle\Security\EntryPoint;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Http\EntryPoint\FormAuthenticationEntryPoint;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * {@inheritDoc}
 */
class GigyaAuthenticationEntryPoint extends FormAuthenticationEntryPoint
{
    /**
     * Constructor.
     *
     * @param HttpKernelInterface $kernel
     * @param HttpUtils           $httpUtils  An HttpUtils instance
     * @param string              $loginPath  The path to the login form
     * @param Boolean             $useForward Whether to forward or redirect to the login form
     */
    public function __construct(HttpKernelInterface $kernel, HttpUtils $httpUtils, $loginPath, $useForward = false)
    {
        parent::__construct($kernel, $httpUtils, $loginPath, $useForward);
    }
}
