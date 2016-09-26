<?php
/**
 * Created by PhpStorm.
 * User: esolitos
 * Date: 26/06/16
 * Time: 16:05
 */

namespace Drupal\entity_collection_db\Plugin\EntityCollectionStorage;


use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\EntityCollectionStorageBase;
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
class Database extends EntityCollectionStorageBase {

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