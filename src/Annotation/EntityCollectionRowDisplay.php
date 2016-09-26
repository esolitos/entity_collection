<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Collection: Row Display item annotation object.
 *
 * @see \Drupal\entity_collection\Plugin\EntityCollectionRowDisplayManager
 * @see plugin_api
 *
 * @Annotation
 */
class EntityCollectionRowDisplay extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the Row Display plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A brief description of the Row Display plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;
}
