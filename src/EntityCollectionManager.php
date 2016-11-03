<?php

namespace Drupal\entity_collection;

use Drupal\Core\Config\ConfigFactory;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\AdminUIManager;
use Drupal\entity_collection\Plugin\ListStyleManager;
use Drupal\entity_collection\Plugin\RowDisplayManager;
use Drupal\entity_collection\Plugin\StorageManager;

/**
 * Class EntityCollectionManager.
 *
 * @package Drupal\entity_collection
 */
class EntityCollectionManager implements EntityCollectionManagerInterface {

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * @var \Drupal\entity_collection\Plugin\AdminUIManager
   */
  protected $adminUIManager;

  /**
   * @var \Drupal\entity_collection\Plugin\StorageManager
   */
  protected $storageManager;

  /**
   * @var \Drupal\entity_collection\Plugin\ListStyleManager
   */
  protected $listStyleManager;

  /**
   * @var \Drupal\entity_collection\Plugin\RowDisplayManager
   */
  protected $rowDisplayManager;

  /**
   * EntityCollectionManager constructor.
   *
   * @param \Drupal\entity_collection\Plugin\AdminUIManager $admin_ui_manager
   * @param \Drupal\entity_collection\Plugin\StorageManager $storage_manager
   * @param \Drupal\entity_collection\Plugin\ListStyleManager $list_style_manager
   * @param \Drupal\entity_collection\Plugin\RowDisplayManager $row_display_manager
   */
  public function __construct(
    AdminUIManager $admin_ui_manager,
    StorageManager $storage_manager,
    ListStyleManager $list_style_manager,
    RowDisplayManager $row_display_manager
  ) {

    $this->adminUIManager = $admin_ui_manager;
    $this->storageManager = $storage_manager;
    $this->listStyleManager = $list_style_manager;
    $this->rowDisplayManager = $row_display_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function getCollection($name) {
    // TODO: Implement getCollection() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getAdminUI(EntityCollectionInterface $collection) {
    return $this->createPluginInstance($collection, 'admin_ui');
  }

  /**
   * {@inheritdoc}
   */
  public function getListStyle(EntityCollectionInterface $collection) {
    return $this->createPluginInstance($collection, 'list_style');
  }

  /**
   * {@inheritdoc}
   */
  public function getRowDisplay(EntityCollectionInterface $collection) {
    return $this->createPluginInstance($collection, 'row_display');
  }

  /**
   * {@inheritdoc}
   */
  public function getStorage(EntityCollectionInterface $collection) {
    return $this->createPluginInstance($collection, 'storage');
  }

  /**
   *
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   * @param string $plugin_type
   *
   * @return \Drupal\entity_collection\Plugin\EntityCollectionPluginBaseInterface|null
   */
  private function createPluginInstance(EntityCollectionInterface $collection, $plugin_type) {
    $plugin = NULL;

    $plugin_id = $collection->get($plugin_type);
    $plugin_settings = $collection->get($plugin_type . '_settings');

    if ( $plugin_id ){
      $plugin = $this->adminUIManager->createInstance($plugin_id, $plugin_settings);
    }

    return $plugin;
  }


}
