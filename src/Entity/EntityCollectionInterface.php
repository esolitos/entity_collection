<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\entity_collection\Plugin\EntityCollectionListStyleInterface;
use Drupal\entity_collection\Plugin\EntityCollectionRowDisplayInterface;
use Drupal\entity_collection\Plugin\StorageInterface;

/**
 * Provides an interface for defining Entity collection entities.
 */
interface EntityCollectionInterface extends ConfigEntityInterface {

  public function getTree();

  public function setContexts(array $contexts);

  public function getContexts();
  
  public function setStorage(StorageInterface $storage);

  /**
   * @return \Drupal\entity_collection\Plugin\StorageInterface
   */
  public function getStorage();

  public function isStorageConfigured();

  public function setListStyle(EntityCollectionListStyleInterface $list_style);

  /**
   * @return \Drupal\entity_collection\Plugin\EntityCollectionListStyleInterface
   */
  public function getListStyle();

  public function isListStyleConfigured();

  public function setRowDisplay(EntityCollectionRowDisplayInterface $row_display);

  /**
   * @return \Drupal\entity_collection\Plugin\EntityCollectionRowDisplayInterface
   */
  public function getRowDisplay();

  public function isRowDisplayConfigured();
}
