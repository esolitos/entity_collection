<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\entity_collection\Plugin\AdminUIInterface;
use Drupal\entity_collection\Plugin\ListStyleInterface;
use Drupal\entity_collection\Plugin\RowDisplayInterface;
use Drupal\entity_collection\Plugin\StorageInterface;

/**
 * Provides an interface for defining Entity collection entities.
 */
interface EntityCollectionInterface extends ConfigEntityInterface {

  public function getTree();

  public function setContexts(array $contexts);

  public function getContexts();


  /**
   * @return \Drupal\entity_collection\Plugin\AdminUIInterface
   */
  public function getAdminUI();

  /**
   * @return bool
   */
  public function isAdminUIConfigured();

  public function setAdminUI(AdminUIInterface $admin_ui);

  public function setStorage(StorageInterface $storage);

  /**
   * @return \Drupal\entity_collection\Plugin\StorageInterface
   */
  public function getStorage();

  /**
   * @return bool
   */
  public function isStorageConfigured();

  public function setListStyle(ListStyleInterface $list_style);

  /**
   * @return \Drupal\entity_collection\Plugin\ListStyleInterface
   */
  public function getListStyle();

  /**
   * @return bool
   */
  public function isListStyleConfigured();

  public function setRowDisplay(RowDisplayInterface $row_display);

  /**
   * @return \Drupal\entity_collection\Plugin\RowDisplayInterface
   */
  public function getRowDisplay();

  /**
   * @return bool
   */
  public function isRowDisplayConfigured();
}
