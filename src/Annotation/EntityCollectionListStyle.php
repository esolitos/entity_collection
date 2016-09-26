<?php

namespace Drupal\entity_collection\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Entity Collection: List Style item annotation object.
 *
 * @see \Drupal\entity_collection\Plugin\EntityCollectionListStyleManager
 * @see plugin_api
 *
 * @Annotation
 */
class EntityCollectionListStyle extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The human-readable name of the List Style plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * A brief description of the List Style plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
