<?php

namespace Proyecto404\GigyaBundle;

use Proyecto404\GigyaBundle\DependencyInjection\Security\Factory\GigyaFactory;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class Proyecto404GigyaBundle
 */
class Proyecto404GigyaBundle extends Bundle
{
    /**
     * {@inheritDoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new GigyaFactory());
    }
}
