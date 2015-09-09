<?php

namespace Proyecto404\GigyaBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GigyaExtension.
 */
class GigyaExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        $that = $this;

        return array(
            new \Twig_SimpleFunction('gigya_initialize', function($lang = 'en') use($that) {
                return $that->renderInitialize($lang);
            }, array('is_safe' => array('html')))
        );
    }

    public function renderInitialize($lang = 'en')
    {
        $apiKey = $this->container->getParameter('proyecto404_gigya.api_key');
        $html = '<script type="text/javascript" src="https://cdns.gigya.com/JS/gigya.js?apikey='.$apiKey.'">';
        $html .= '{ lang: "'.$lang.'"}';
        $html .= '</script>';

        return $html;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gigya_extension';
    }
}
