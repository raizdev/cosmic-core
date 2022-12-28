<?php
namespace Orion\Core\Factory;

use Orion\Core\Model\Query\DataObjectManager;
use Illuminate\Database\Capsule\Manager;

/**
 * Class DataObjectManagerFactory
 *
 * @package Orion\Core\Factory
 */
class DataObjectManagerFactory
{
    /**
     * Creates new DataObject instance.
     *
     * @param string $entity
     * @return DataObjectManager
     */
    public function create(string $entity): DataObjectManager
    {
        $table = $entity::TABLE;
        $manager = Manager::table($table);

        $dataObjectManager = new DataObjectManager(
            $manager->getConnection(),
            $manager->getGrammar(),
            $manager->getProcessor()
        );

        $dataObjectManager->from($table);
        $dataObjectManager->setEntity($entity);

        return $dataObjectManager;
    }
}
