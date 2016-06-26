<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Entity Collection: List Style plugins.
 */
abstract class EntityCollectionListStyleBase extends EntityCollectionPluginBase implements EntityCollectionListStyleInterface {


  /**
   * Use a max depth of 0 (disabled) by default.
   */
  public function getMaxDepth() {
    return 0;
  }

  /**
   * Use reordering by default.
   */
  public function useReordering() {
    return TRUE;
  }

}
