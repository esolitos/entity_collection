<?php

namespace Drupal\entity_collection_db\Plugin\EntityCollection\Storage;

use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\StorageBase;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Class Database
 *
 * @EntityCollectionStorage(
 *   id = "database",
 *   label = @Translation("Database Storage"),
 *   description = @Translation("Stores the Entity Collection content in a system database")
 * )
 */
class Database extends StorageBase {

  /**
   * {@inheritdoc}
   */
  public function store(EntityCollectionInterface $collection, TreeNodeInterface $tree) {
    // TODO: Implement store() method.
  }

  /**
   * {@inheritdoc}
   */
  public function load(EntityCollectionInterface $collection) {
    // TODO: Implement load() method.
  }

  /**
   * {@inheritdoc}
   */
  public function truncate(EntityCollectionInterface $collection) {
    // TODO: Implement truncate() method.
  }

}