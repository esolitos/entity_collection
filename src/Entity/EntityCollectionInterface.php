<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Entity collection entities.
 */
interface EntityCollectionInterface extends ConfigEntityInterface {

  
  public function setStorage();

  public function getStorage();

  public function setListStyle();

  public function getListStyle();

  public function setRowDisplay();

  public function getRowDisplay();
}
