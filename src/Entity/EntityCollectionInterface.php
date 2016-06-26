<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\entity_collection\Plugin\EntityCollectionListStyleInterface;
use Drupal\entity_collection\Plugin\EntityCollectionRowDisplayInterface;
use Drupal\entity_collection\Plugin\EntityCollectionStorageInterface;

/**
 * Provides an interface for defining Entity collection entities.
 */
interface EntityCollectionInterface extends ConfigEntityInterface {

  
  public function setStorage(EntityCollectionStorageInterface $storage);

  public function getStorage();

  public function setListStyle(EntityCollectionListStyleInterface $list_style);

  public function getListStyle();

  public function setRowDisplay(EntityCollectionRowDisplayInterface $row_display);

  public function getRowDisplay();
}
