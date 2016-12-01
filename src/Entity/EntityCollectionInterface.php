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
  /**
   * Checks if the Entity Collection has an Admin UI plugin defined.
   *
   * @return bool
   */
  public function isAdminUIConfigured();

  /**
   * @param \Drupal\entity_collection\Plugin\AdminUIInterface $admin_ui
   * @return void
   */
  public function setAdminUI(AdminUIInterface $admin_ui);

  public function setStorage(StorageInterface $storage);

  /**
   * Checks if the Entity Collection has an backend Storage plugin defined.
   *
   * @return bool
   */
  public function isStorageConfigured();

  public function setListStyle(ListStyleInterface $list_style);

  /**
   * Checks if the Entity Collection has an List Style plugin defined.
   *
   * @return bool
   */
  public function isListStyleConfigured();

  public function setRowDisplay(RowDisplayInterface $row_display);

  /**
   * Checks if the Entity Collection has an Row Display plugin defined.
   *
   * @return bool
   */
  public function isRowDisplayConfigured();
}
