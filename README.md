# TicketparkDynamicRoleBundle

This Symfony2 bundle ads functionalities to dynamically create Symfony Security roles and persist them using Doctrine DBAL.
We use it to generate roles that can be given permissions to particular model instances. For example, an operator role for a blog post might be
```php
ROLE_POST_<public_identifier>_OPERATOR
```
it can then be given an operator ACE for that blog post instance and be given to a Group or User instance.

## Todos
* Add unit tests
* Improve documentation

## Functionalities
* RoleProvider (Service)
    * Manages dynamic Symfony Security roles
* RoleHierarchy (Service)
    * Extends Symfony\Component\Security\Core\Role\RoleHierarchy
    * Fetches role descriptions from a configurable Doctrine DBAL table and merges them with the roles defined in the Symfony config

## Installation

Add TicketparkDynamicRoleBundle in your composer.json:

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

## Caching
To enable HierarchyRole map caching override `ticketpark.dynamic_role_cache` service
For example:

```
    ticketpark.dynamic_role_cache:
        class: Doctrine\Common\Cache\FilesystemCache
        public: false
        arguments:
            - "%kernel.cache_dir%/dynamic_role"
```

## License


This bundle is under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE
