<?php

namespace Drupal\entity_collection;
use Drupal\entity_collection\Entity\EntityCollection;

/**
 * Interface EntityCollectionManagerInterface.
 *
 * @package Drupal\entity_collection
 */
interface EntityCollectionManagerInterface {


  public function getCollection($name);

  public function getStorage(EntityCollection $collection);

  public function getRowDisplay(EntityCollection $collection);

  public function getListStyle(EntityCollection $collection);

}
