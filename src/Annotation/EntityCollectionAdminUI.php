<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Collection: Admin UI item annotation object.
 *
 * @see \Drupal\entity_collection\Plugin\EntityCollectionAdminUIManager
 * @see plugin_api
 *
 * @Annotation
 */
class EntityCollectionAdminUI extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A brief description of the Admin UI plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;
}
