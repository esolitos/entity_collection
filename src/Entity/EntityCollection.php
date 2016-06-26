<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
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
   * @var EntityCollectionStorageInterface
   */
  protected $storage;

  /**
   * @var
   */
  protected $storage_settings;

  /**
   * @var EntityCollectionListStyleInterface
   */
  protected $list_style;

  /**
   * @var
   */
  protected $list_style_settings;

  /**
   * @var EntityCollectionRowDisplayInterface
   */
  protected $row_display;

  /**
   * @var
   */
  protected $row_display_settings;

  /**
   * @return mixed
   */
  public function getStorage() {
    return $this->storage;
  }

  /**
   * @param EntityCollectionStorageInterface $storage
   */
  public function setStorage(EntityCollectionStorageInterface $storage) {
    $this->storage = $storage;
  }

  /**
   * @return EntityCollectionListStyleInterface
   */
  public function getListStyle() {
    return $this->list_style;
  }

  /**
   * @param EntityCollectionListStyleInterface $list_style
   */
  public function setListStyle(EntityCollectionListStyleInterface $list_style) {
    $this->list_style = $list_style;
  }

  /**
   * @return EntityCollectionRowDisplayInterface
   */
  public function getRowDisplay() {
    return $this->row_display;
  }

  /**
   * @param EntityCollectionRowDisplayInterface $row_display
   */
  public function setRowDisplay(EntityCollectionRowDisplayInterface $row_display) {
    $this->row_display = $row_display;
  }

  


}
