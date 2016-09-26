<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Entity Collection: Row Display plugins.
 */
abstract class RowDisplayBase extends EntityCollectionPluginBase implements RowDisplayInterface {


  /**
   * Not allow "style per row" by default.
   */
  public function useStylePerRow() {
    return FALSE;
  }
}
