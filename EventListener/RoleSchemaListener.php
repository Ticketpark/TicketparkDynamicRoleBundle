<?php

namespace Ticketpark\DynamicRoleBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Merges role schema into the given schema.
 *
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class RoleSchemaListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $args)
    {
        $schema = $args->getSchema();
        $this->container->get('ticketpark.role.dbal.schema')->addToSchema($schema);
    }
}
