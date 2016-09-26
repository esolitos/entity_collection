<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;
use Drupal\entity_collection\Annotation\EntityCollectionStorage;
use Drupal\entity_collection\Plugin\EntityCollectionListStyleInterface;
use Drupal\entity_collection\Plugin\EntityCollectionRowDisplayInterface;
use Drupal\entity_collection\Plugin\EntityCollectionStorageInterface;

/**
 * Defines the Entity collection entity.
 *
 * @ConfigEntityType(
 *   id = "entity_collection",
 *   label = @Translation("Entity Collection"),
 *   handlers = {
 *     "list_builder" = "Drupal\entity_collection\EntityCollectionListBuilder",
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
 *   admin_permission = "administer site configuration",
 *   links = {
 *     "canonical" = "/admin/structure/entity_collection/{entity_collection}",
 *     "add-form" = "/admin/structure/entity_collection/add",
 *     "edit-form" = "/admin/structure/entity_collection/{entity_collection}/edit",
 *     "delete-form" = "/admin/structure/entity_collection/{entity_collection}/delete",
 *     "collection" = "/admin/structure/entity_collection"
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
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
  public function isStorageConfigured() {
    return !empty($this->storage);
  }


  /**
   * @return mixed
   */
  public function getStorage() {
    if ( $this->storage ) {
      return \Drupal::service('plugin.manager.entity_collection_storage')->createInstance($this->storage, $this->storage_settings);
    }

    return NULL;
  }

  /**
   * @param EntityCollectionStorageInterface $storage
   */
  public function setStorage(EntityCollectionStorageInterface $storage) {
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
   * @return EntityCollectionListStyleInterface
   */
  public function getListStyle() {
    if ( $this->list_style ) {
      return \Drupal::service('plugin.manager.entity_collection_list_style')->createInstance($this->list_style, $this->list_style_settings);
    }

    return NULL;
  }

  /**
   * @param EntityCollectionListStyleInterface $list_style
   */
  public function setListStyle(EntityCollectionListStyleInterface $list_style) {
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
   * @return EntityCollectionRowDisplayInterface
   */
  public function getRowDisplay() {
    if ( $this->row_display ) {
      return \Drupal::service('plugin.manager.entity_collection_row_display')->createInstance($this->row_display, $this->row_display_settings);
    }

    return NULL;
  }

  /**
   * @param EntityCollectionRowDisplayInterface $row_display
   */
  public function setRowDisplay(EntityCollectionRowDisplayInterface $row_display) {
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
