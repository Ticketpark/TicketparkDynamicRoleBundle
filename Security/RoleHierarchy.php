<?php

namespace Ticketpark\DynamicRoleBundle\Security;

use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
/**
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class RoleHierarchy extends BaseRoleHierarchy
{
    protected $connection;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $hierarchy, $connection)
    {
        $this->connection = $connection;

        return parent::__construct(array_merge($hierarchy, $this->buildHierarchy()));
    }

    /**
     * Merges the $hierarchies from config and db.
     */
    protected function buildHierarchy()
    {
        $hierarchy = array();

        foreach ($this->connection->executeQuery($this->getFindRolesSql())->fetchAll() as $data) {
            if ($data['parent_role']) {
                if (!isset($hierarchy[$data['parent_role']])) {
                    $hierarchy[$data['parent_role']] = array();
                }
                $hierarchy[$data['parent_role']][] = $data['role'];
            } else {
                if (!isset($hierarchy[$data['role']])) {
                    $hierarchy[$data['role']] = array();
                }
            }
        }

        return $hierarchy;
    }

    /**
     * Constructs the SQL for retrieving roles
     *
     * @return string
     */
    protected function getFindRolesSql()
    {
        $query = <<<QUERY
            SELECT r.name AS role, p.name AS parent_role
            FROM role as r
            LEFT JOIN role as p ON p.id = r.parent_id
QUERY;

        return $query;
    }
}
