<?php

namespace Ticketpark\DynamicRoleBundle\Security;

use Doctrine\DBAL\Driver\Connection;

/**
 * A RoleProvider. Handles roles stored via DBAL as strings
 *
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class RoleProvider
{
    protected $options;
    protected $connection;

    /**
     * Constructor.
     *
     * @param array         $options
     * @param Connection    $connection
     */
    public function __construct(array $options, Connection $connection)
    {
        $this->options = $options;
        $this->connection = $connection;
    }

    /**
     * Returns the role name
     *
     * @param string $name
     * @return string
     */
    public function findRole($name, $parentRole = null)
    {
        if ($parentRole) {
            $query = <<<QUERY
                SELECT r.name AS role
                FROM {$this->options['role_table_name']} as r
                LEFT JOIN {$this->options['role_table_name']} AS p ON r.parent_id = p.id
                WHERE r.name = ? AND p.name = ?
QUERY;

            return $this->connection->fetchColumn($query, array($name, $parentRole), 0);
        } else {
            $query = <<<QUERY
                SELECT r.name AS role
                FROM {$this->options['role_table_name']} as r
                WHERE r.name = ?
QUERY;

            return $this->connection->fetchColumn($query, array($name), 0);
        }
    }

    public function createRole($name, $parentRole = null)
    {
        $query = <<<QUERY
            SELECT r.id AS id
            FROM {$this->options['role_table_name']} as r
            WHERE r.name = ?
QUERY;

        $id = $this->connection->fetchColumn($query, array($name), 0);

        if ($parentRole) {
            $parentId = $this->connection->fetchColumn($query, array($parentRole), 0) ?: null;
        } else {
            $parentId = null;
        }

        if ($id) {
            if ($parentId) {
                $this->connection->update($this->options['role_table_name'], array('name' => $name, 'parent_id' => $parentId), array('id' => $id));
            } else {
                $this->connection->update($this->options['role_table_name'], array('name' => $name), array('id' => $id));
            }
        } else {
            $this->connection->insert($this->options['role_table_name'], array('name' => $name, 'parent_id' => $parentId));
        }

        return $name;
    }
}
