<?php

namespace Drupal\entity_collection\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Entity collection entity.
 *
 * @ConfigEntityType(
 *   id = "entity_collection",
 *   label = @Translation("Entity collection"),
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
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/entity_collection/{entity_collection}",
 *     "add-form" = "/admin/structure/entity_collection/add",
 *     "edit-form" = "/admin/structure/entity_collection/{entity_collection}/edit",
 *     "delete-form" = "/admin/structure/entity_collection/{entity_collection}/delete",
 *     "collection" = "/admin/structure/entity_collection"
 *   }
 * )
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
   * TODO: Type?
   *
   * @var
   */
  protected $treeStyle;

  /**
   * TODO: Type?
   *
   * @var
   */
  protected $nodeStyle;
}
