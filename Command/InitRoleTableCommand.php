<?php

namespace Ticketpark\DynamicRoleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\SchemaException;

/**
 * Installs the table required by the dynamic roles
 *
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class InitRoleTableCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('ticketpark:security:init-role-table')
            ->setDescription('Mounts a database table to store dynamic roles')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command mounts a database table to store dynamic roles.

<info>php %command.full_name%</info>
EOF
            )
        ;
    }

    /**
     * @see Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();

        $connection = $container->get('security.acl.dbal.connection');
        $schema = $container->get('ticketpark.role.dbal.schema');

        // TODO: this was copied from InitAclCommand, is it necessary here?
        try {
            $schema->addToSchema($connection->getSchemaManager()->createSchema());
        } catch (SchemaException $e) {
            $output->writeln("Aborting: ".$e->getMessage());

            return 1;
        }

        foreach ($schema->toSql($connection->getDatabasePlatform()) as $sql) {
            $connection->exec($sql);
        }

        $output->writeln('dynamic role table has been initialized successfully.');
    }
}
