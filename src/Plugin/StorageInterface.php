<?php

namespace Drupal\entity_collection\Plugin;


use Drupal\entity_collection\CollectionTree\TreeNodeInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

/**
 * Defines an interface for EntityCollection Storage plugins.
 */
interface StorageInterface extends EntityCollectionPluginBaseInterface {

  /**
   * Stores an entity collection in the backend.
   *
   * @param EntityCollectionInterface $collection
   *   The collection to which the content belongs.
   * @param TreeNodeInterface $treeNode
   *   The Tree to be saved
   */
  public function store(TreeNodeInterface $treeNode);

  /**
   * Retrieve the content of a collection.
   *
   * @param EntityCollectionInterface $collection
   *   The collection to which the content belongs.
   */
  public function load();
  
  /**
   * Remove all content in the entity collection.
   *
   * @param EntityCollectionInterface $collection
   */
  public function truncate();
}
