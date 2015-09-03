GigyaBundle
======

##Installation

You can install this bundle using composer

``` bash
$ php composer.phar require proyecto404/gigya-bundle "dev-master"
```

or add the package to your `composer.json` file directly.

Composer will install the bundle to your project's `vendor/proyecto404` directory.

After you have installed the package, you just need to add the bundle to your `AppKernel.php` file:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Proyecto404\GigyaBundle\Proyecto404GigyaBundle(),
    );
}
```

  
License
-------

This bundle is under the MIT license. See the complete license in the bundle `LICENSE` file.
