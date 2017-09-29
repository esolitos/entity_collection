<?php

namespace Drupal\Tests\entity_collection\Unit;

use Drupal\entity_collection\Annotation\EntityCollectionStorage;
use Drupal\entity_collection\CollectionTree\TreeNode;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\KernelTests\KernelTestBase;
use Drupal\migrate\Plugin\migrate\destination\EntityContentBase;
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
    'system',
    'node',
    'user'
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

  public function test can save single entry() {
    $collection = EntityCollection::create([
      'storage' => 'database',
    ]);

    $plugin_id = 'database';
    $plugin_settings = ['database'=>'default'];
    /** @var \Drupal\entity_collection\Plugin\EntityCollection\Storage\Database $storage */
    $storage = $this->storagePluginManager->createInstance($plugin_id, $plugin_settings, $collection);

    $nid = rand();
//    $node_entity = $this->createRandomNode($nid);

    $tree = new TreeNode();
    $tree->appendChild(TreeNode::createChild($tree, $this->createRandomNode($nid)));

    $storage->store($collection, $tree);

    $query = \Drupal::database()->select('entity_collection_storage');
    $query->condition('entity_type', 'node')
      ->condition('entity_id', $nid);

    $query_count = clone $query;
    $row_count = $query_count->countQuery()
      ->execute()
      ->fetchField();

    $this->assertEquals(1, $row_count, 'One record is added to the table.');

    $row = $query->execute()->fetch();
    $this->assertEquals($nid, $row->entity_id);

  }


  private function createRandomNode(int $nid): Node {
    return Node::create([
      'type' => 'article',
      'id' => $nid,
      'title' => $this->randomString(),
    ]);
  }

}
