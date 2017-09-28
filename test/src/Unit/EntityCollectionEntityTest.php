<?php

namespace Drupal\entity_collection\Tests;


use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\Tests\UnitTestCase;

/**
 * Tests EntityCollection functionality.
 *
 * @coversDefaultClass \Drupal\entity_collection\Entity\EntityCollection
 * @group EntityCollectionEntityTest
 */
class EntityCollectionEntityTest extends UnitTestCase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('entity_collection');


  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
  }

  public function test can create new clean entity collection() {
    $ec = new EntityCollection([], 'entity_collection');

    $this->assertFalse($ec->isAdminUIConfigured(), 'Admin UI is not configured.');
    $this->assertFalse($ec->isListStyleConfigured(), 'List Style is not configured.');
    $this->assertFalse($ec->isRowDisplayConfigured(), 'Row Display is not configured.');
    $this->assertFalse($ec->isStorageConfigured(), 'Storage is not configured.');
  }

}
