# DynamicRoleBundle

This Symfony2 bundle ads functionalities to dynamically create Symfony Security roles and persist them using Doctrine DBAL.

## Functionalities
* RoleProvider (Service)
    * Manages dynamic Symfony Security roles
* RoleHierarchy (Service)
    * Extends Symfony\Component\Security\Core\Role\RoleHierarchy
    * Fetches role descriptions from a configurable Doctrine DBAL table and merges them with the roles defined in the Symfony config

## Installation

Add TicketparkFileBundle in your composer.json:

```js
{
    "require": {
        "ticketpark/dynamic-role-bundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update ticketpark/dynamic-role-bundle
```

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Ticketpark\DynamicRoleBundle\TicketparkDynamicRoleBundle(),
    );
}
```

## License


This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
