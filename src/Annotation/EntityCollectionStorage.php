<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Collection: Storage item annotation object.
 *
 * @see \Drupal\entity_collection\Plugin\EntityCollectionStorageManager
 * @see plugin_api
 *
 * @Annotation
 */
class EntityCollectionStorage extends Plugin {


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
   * A short description for the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;
}
