<?php

namespace Ticketpark\DynamicRoleBundle\Security\Dbal;

use Doctrine\DBAL\Schema\Schema as BaseSchema;
use Doctrine\DBAL\Connection;

/**
 * The schema used for the dynamic roles.
 */
class Schema extends BaseSchema
{
    protected $options;

    /**
     * Constructor
     *
     * @param array      $options    the names for tables
     * @param Connection $connection
     */
    public function __construct(array $options, Connection $connection = null)
    {
        $schemaConfig = null === $connection ? null : $connection->getSchemaManager()->createSchemaConfig();

        parent::__construct(array(), array(), $schemaConfig);

        $this->options = $options;

        $this->addRoleTable();
    }

    /**
     * Adds the role table to the schema
     */
    protected function addRoleTable()
    {
        $table = $this->createTable($this->options['role_table_name']);
        $table->addColumn('id', 'integer', array('unsigned' => true, 'autoincrement' => 'auto'));
        $table->addColumn('parent_id', 'integer', array('unsigned' => true, 'notnull' => false));
        $table->addColumn('name', 'string', array('length' => 200));
        $table->setPrimaryKey(array('id'));
    }

    /**
     * Merges this with the given schema.
     *
     * @param BaseSchema $schema
     */
    public function addToSchema(BaseSchema $schema)
    {
        foreach ($this->getTables() as $table) {
            $schema->_addTable($table);
        }

        foreach ($this->getSequences() as $sequence) {
            $schema->_addSequence($sequence);
        }
    }
}
