<?php

namespace Ticketpark\DynamicRoleBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class TicketparkDynamicRoleExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setAlias('ticketpark.role_table.connection', sprintf('doctrine.dbal.%s_connection', $config['role_table']['connection']));
        $container->setParameter('ticketpark.role_table.name', $config['role_table']['name']);
        $container
            ->getDefinition('ticketpark.role.dbal.schema_listener')
            ->addTag('doctrine.event_listener', array(
                'connection' => $config['role_table']['connection'],
                'event'      => 'postGenerateSchema',
                'lazy'       => true
            ))
        ;
    }
}
