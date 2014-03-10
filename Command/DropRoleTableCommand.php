<?php

namespace Ticketpark\DynamicRoleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\SchemaException;

/**
 * Drops the table required by the dynamic roles
 *
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class DropRoleTableCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('ticketpark:security:drop-role-table')
            ->setDescription('Drops the database table used to store dynamic roles')
            ->setHelp(<<<EOF
The <info>%command.name%</info> command drops the database table used to store dynamic roles.

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

        foreach ($schema->toDropSql($connection->getDatabasePlatform()) as $sql) {
            $connection->exec($sql);
        }

        $output->writeln('dynamic role table has been dropped successfully.');
    }
}
