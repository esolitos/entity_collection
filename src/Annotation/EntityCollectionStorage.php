<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Collection: Storage item annotation object.
 *
 * @see \Drupal\entity_collection\Plugin\StorageManager
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
   * The human-readable name of the Storage plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A brief description of the Storage plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;
}
