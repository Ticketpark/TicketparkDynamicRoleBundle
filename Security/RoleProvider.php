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
    protected $connection;
    protected $options;

    /**
     * Constructor.
     *
     * @param Connection    $connection
     * @param array         $options
     */
    public function __construct(Connection $connection, $options)
    {
        $this->connection = $connection;
        $this->options = $options;
    }

    /**
     * Returns the role name
     *
     * @param string $name
     * @return string
     */
    public function findRole($name)
    {
        $query = <<<QUERY
            SELECT r.name AS role
            FROM role as r
            WHERE r.name = ?
QUERY;
        return $this->connection->fetchColumn($query, array($name), 0);
    }

    public function createRole($name, $parentRole = null)
    {
        $queryParent = <<<QUERY
            SELECT r.id AS id
            FROM role as r
            WHERE r.name = ?
QUERY;

        if ($parentRole) {
            $parentId = $this->connection->fetchColumn($queryParent, array($parentRole), 0) ?: null;
        } else {
            $parentId = null;
        }

        $this->connection->insert('role', array('name' => $name, 'parent_id' => $parentId));

        return $name;
    }
}
