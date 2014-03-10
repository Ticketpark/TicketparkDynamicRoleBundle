<?php

namespace Ticketpark\DynamicRoleBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\DBAL\Schema\SchemaException;

/**
 * 
 */
class TruncateRoleTableCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('ticketpark:security:truncate-role-table')
            ->setDescription('Truncates all content form the dynamic role table')
            ->setHelp(<<<EOF
The <info>%command.name%</info> truncates all content form the dynamic role table.

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

        $connection = $container->get('ticketpark.role_table.connection');
        $schema = $container->get('ticketpark.role.dbal.schema');

        $connection->beginTransaction();

        try {
            $connection->query('SET FOREIGN_KEY_CHECKS=0');

            foreach ($schema->getTables() as $table) {
                $sql = $connection->getDatabasePlatform()->getTruncateTableSql($table->getName());
                $connection->executeUpdate($sql);
            }

            $connection->query('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            $connection->rollback();
        }

        $output->writeln('dynamic role table has been truncated.');
    }
}
