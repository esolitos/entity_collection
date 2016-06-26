<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Entity Collection: Row Display plugins.
 */
abstract class EntityCollectionRowDisplayBase extends EntityCollectionPluginBase implements EntityCollectionRowDisplayInterface {


  /**
   * Not allow "style per row" by default.
   */
  public function useStylePerRow() {
    return FALSE;
  }
}
