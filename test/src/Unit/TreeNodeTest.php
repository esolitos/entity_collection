<?php

namespace Drupal\entity_collection\Tests;


use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\entity_collection\TreeNodeInterface;
use Drupal\node\Entity\Node;
use Drupal\Tests\UnitTestCase;
use Drupal\entity_collection\TreeNode;

/**
 * Tests EntityCollection functionality.
 *
 * @coversDefaultClass \Drupal\entity_collection\TreeNode
 * @group EntityCollectionEntityTest
 */
class TreeNodeTest extends UnitTestCase {

  /**
   * @var \Drupal\entity_collection\TreeNode
   */
  private $tree;

  /**
   * @var \Drupal\Core\Entity\ContentEntityBase
   */
  private $entity;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();

    $this->entity = $this->getMockBuilder('\Drupal\Core\Entity\ContentEntityBase')
      ->disableOriginalConstructor()
      ->getMock();
  }

  /**
   * Simply tests some basic involved in a Entity Collection creation
   *
   * @covers ::__construct
   */
  public function testCreateEmpty() {
    $treeNode = new TreeNode();

    $this->assertNotNull($treeNode, 'The TreeNode is created.');

    $count = 0;
    foreach ($treeNode as $item) {
      $count++;
    }

    $this->assertEquals(0, $count, 'New TreeNode should contain zero items.');

    return $treeNode;
  }

  /**
   * Test creation of a TreeNode from an entity
   *
   * @covers ::create
   */
  public function testCreateTree() {
    $treeNode = TreeNode::create($this->entity);

    $this->assertInstanceOf(TreeNodeInterface::class, $treeNode, 'Creates a TreeNode from an Entity');
    $this->assertInstanceOf(ContentEntityInterface::class, $treeNode->current(), 'The current element is an Entity');

    return $treeNode;
  }
}
