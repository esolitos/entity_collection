<?php

namespace Drupal\Tests\entity_collection\Unit;

use Drupal\entity_collection\CollectionTree\TreeNode;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\entity_collection\Plugin\EntityCollection\Storage\Database;
use Drupal\KernelTests\KernelTestBase;
use Drupal\node\Entity\Node;

/**
 * Tests Database storage backend for EntityCollection functionality.
 *
 *
 * @coversDefaultClass \Drupal\entity_collection\Plugin\EntityCollection\Storage\Database
 * @group EntityCollectionEntityTest
 */
class DatabaseTest extends KernelTestBase {

  public static $modules = [
    'entity_collection',
  ];

  /**
   * @var \Drupal\entity_collection\Plugin\StorageManager
   */
  protected $storagePluginManager;


  protected function setUp() {
    parent::setUp();
    $this->installSchema('entity_collection', [
      'entity_collection_storage',
      'entity_collection_storage_properties'
    ]);

    $this->storagePluginManager = \Drupal::service('plugin.manager.entity_collection_storage');
  }

  /**
   *
   */
  public function test can save single entry() {
    $collection = EntityCollection::create([
      'id' => $this->randomMachineName(),
      'storage' => 'database',
    ]);

    $plugin_id = 'database';
    $plugin_settings = ['database'=>'default'];
    /** @var \Drupal\entity_collection\Plugin\EntityCollection\Storage\Database $storage */
    $storage = $this->storagePluginManager->createInstance($plugin_id, $plugin_settings, $collection);

    $tree = new TreeNode();

    $id = rand();
    $child = TreeNode::createChild($tree, $this->createEntity($id));
    $tree->appendChild($child);

    $storage->store($tree);

    $query = \Drupal::database()
      ->select(Database::STORAGE_COLLECTION_TABLE, 'f');
    $query->fields('f')
      ->condition('entity_id', $id);

    $query_count = clone $query;
    $row_count = $query_count->countQuery()
      ->execute()
      ->fetchField();

    $this->assertEquals(1, $row_count, 'One record is added to the table.');


    $row = $query->execute()->fetch();
    $this->assertEquals($id, $row->entity_id);

  }


  private function createEntity(int $entity_id = 0, $class = Node::class, $type = 'node', $bundle = 'article', $methods = []) {
    if ($entity_id === 0) {
      $entity_id = rand();
    }

    $node = $this->getMockBuilder($class)
      ->disableOriginalConstructor()
      ->getMock();

    $node->method('id')->willReturn($entity_id);
    $node->method('getEntityTypeId')->willReturn($type);
    $node->method('bundle')->willReturn($bundle);

    foreach ($methods as $name => $return) {
      $node->method($name)->willReturn($return);
    }

    return $node;
  }

}
