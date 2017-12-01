<?php

namespace Ticketpark\DynamicRoleBundle\Security;

use Symfony\Component\Security\Core\Role\RoleHierarchy as BaseRoleHierarchy;
use Doctrine\Common\Cache\Cache;


/**
 * @author Stefan Paschke <stefan.paschke@gmail.com>
 */
class RoleHierarchy extends BaseRoleHierarchy
{
    const CACHE_KEY = 'ticketpark_dynamic_role_map';

    protected $options;
    protected $connection;
    /**
     * @var Cache
     */
    private $cache;

    /**
     * {@inheritdoc}
     */
    public function __construct(array $options, array $hierarchy, $connection, Cache $cache)
    {
        $this->options = $options;
        $this->connection = $connection;
        $this->cache = $cache;

        parent::__construct(array_merge($hierarchy, $this->buildHierarchy()));
    }

    /**
     * Merges the $hierarchies from config and db.
     */
    protected function buildHierarchy()
    {
        $hierarchy = array();

        if ($this->connection->getSchemaManager()->tablesExist(array($this->options['role_table_name']))) {
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
            FROM {$this->options['role_table_name']} as r
            LEFT JOIN {$this->options['role_table_name']} as p ON p.id = r.parent_id
QUERY;

        return $query;
    }

    protected function buildRoleMap()
    {
        if ($this->cache->contains(self::CACHE_KEY)) {
            $this->map = $this->cache->fetch(self::CACHE_KEY);

            return;
        }

        parent::buildRoleMap();

        $this->cache->save(self::CACHE_KEY, $this->map, 0);
    }
}
