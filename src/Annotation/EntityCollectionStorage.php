<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a EntityCollection Storage item annotation object.
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

}
