<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\entity_collection\Plugin\AdminUIInterface;
use Drupal\entity_collection\Plugin\ListStyleInterface;
use Drupal\entity_collection\Plugin\RowDisplayInterface;
use Drupal\entity_collection\Plugin\StorageInterface;

/**
 * Defines the Entity collection entity.
 *
 * @ConfigEntityType(
 *   id = "entity_collection",
 *   label = @Translation("Entity Collection"),
 *   handlers = {
 *     "list_builder" = "Drupal\entity_collection\EntityCollectionListBuilder",
 *     "access" = "Drupal\entity_collection\EntityCollectionAccessController",
 *     "form" = {
 *       "add" = "Drupal\entity_collection\Form\EntityCollectionForm",
 *       "edit" = "Drupal\entity_collection\Form\EntityCollectionForm",
 *       "delete" = "Drupal\entity_collection\Form\EntityCollectionDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\entity_collection\EntityCollectionHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "entity_collection",
 *   links = {
 *     "canonical" = "/admin/structure/entity_collection/{entity_collection}/content",
 *     "add-form" = "/admin/structure/entity_collection/add",
 *     "edit-form" = "/admin/structure/entity_collection/{entity_collection}/edit",
 *     "delete-form" = "/admin/structure/entity_collection/{entity_collection}/delete",
 *     "collection" = "/admin/structure/entity_collection/collections"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "admin_ui",
 *     "admin_ui_settings",
 *     "storage",
 *     "storage_settings",
 *     "list_style",
 *     "list_style_settings",
 *     "row_display",
 *     "row_display_settings",
 *   }
 * )
 *
 * @Note: take a look at EntityWithPluginCollectionInterface
 */
class EntityCollection extends ConfigEntityBase implements EntityCollectionInterface {

  /**
   * The Entity collection ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Entity collection label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Admin UI plugin id
   *
   * @var string
   */
  protected $admin_ui;

  /**
   * The Admin UI plugin settings (if any).
   *
   * @var array
   */
  protected $admin_ui_settings = [];

  /**
   * The storage plugin id.
   *
   * @var string
   */
  protected $storage;

  /**
   * The storage plugin settings
   *
   * @var array
   */
  protected $storage_settings = [];

  /**
   * The List Style plugin id.
   *
   * @var string
   */
  protected $list_style;

  /**
   * The list style plugin settings
   *
   * @var array
   */
  protected $list_style_settings = [];


  /**
   * The List Style plugin id.
   *
   * @var string
   */
  protected $row_display;

  /**
   * The Row Display plugin settings
   *
   * @var array
   */
  protected $row_display_settings = [];


  /**
   * Contains the contexts for this collection
   *
   * @var array
   */
  protected $contexts = [];


  /**
   * {@inheritdoc}
   */
  public function isAdminUIConfigured() {
    return !empty($this->admin_ui);
  }

  /**
   * {@inheritdoc}
   */
  public function setAdminUI(AdminUIInterface $admin_ui) {
    $this->admin_ui = $admin_ui->getPluginId();
    $this->admin_ui_settings = $admin_ui->getConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function isStorageConfigured() {
    return !empty($this->storage);
  }

  /**
   * {@inheritdoc}
   */
  public function setStorage(StorageInterface $storage) {
    $this->storage = $storage->getPluginId();
    $this->storage_settings = $storage->getConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function isListStyleConfigured() {
    return !empty($this->list_style);
  }

  /**
   * {@inheritdoc}
   */
  public function setListStyle(ListStyleInterface $list_style) {
    $this->list_style = $list_style->getPluginId();
    $this->list_style_settings = $list_style->getConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function isRowDisplayConfigured() {
    return !empty($this->row_display);
  }

  /**
   * @param RowDisplayInterface $row_display
   */
  public function setRowDisplay(RowDisplayInterface $row_display) {
    $this->row_display = $row_display->getPluginId();
    $this->row_display_settings = $row_display->getConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function getContexts() {
    return $this->contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function setContexts(array $contexts) {
    $this->contexts = $contexts;
  }

  /**
   * {@inheritdoc}
   */
  public function getTree() {
    // TODO: Implement getTree() method.
  }

}
