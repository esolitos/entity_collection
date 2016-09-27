<?php

namespace Drupal\entity_collection;

use Drupal\entity_collection\Entity\EntityCollectionInterface;

/**
 * Interface EntityCollectionManagerInterface.
 *
 * @package Drupal\entity_collection
 */
interface EntityCollectionManagerInterface {


  /**
   * Loads an Entity Collection based on it's machine name if it can find it.
   * Otherwise returns null for convenience.
   *
   * @param string $name The machine name of the Entity Collection
   *
   * @return \Drupal\entity_collection\Entity\EntityCollectionInterface|null
   */
  public function getCollection($name);

  /**
   * Returns the plugin that will handle the Admin Interface generation for the
   * given Entity Collection.
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   *
   * @return \Drupal\entity_collection\Plugin\AdminUIInterface
   */
  public function getAdminUI(EntityCollectionInterface $collection);

  /**
   * Returns the plugin that handles the long-term Storage for the given Entity
   * Collection.
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   *
   * @return \Drupal\entity_collection\Plugin\StorageInterface
   */
  public function getStorage(EntityCollectionInterface $collection);

  /**
   * Returns the plugin that handles the rendering of each single Row of the given
   * Entity Collection.
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   *
   * @return \Drupal\entity_collection\Plugin\RowDisplayInterface
   */
  public function getRowDisplay(EntityCollectionInterface $collection);

  /**
   * Returns the plugin that handles the rendering of the wrapping list for the
   * given Entity Collection.
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   *
   * @return \Drupal\entity_collection\Plugin\ListStyleInterface
   */
  public function getListStyle(EntityCollectionInterface $collection);

}
