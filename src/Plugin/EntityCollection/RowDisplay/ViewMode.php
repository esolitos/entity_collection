<?php

namespace Drupal\entity_collection\Plugin\EntityCollection\RowDisplay;

use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\RowDisplayBase;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Class ViewMode
 *
 * @EntityCollectionRowDisplay(
 *   id = "view_mode",
 *   label = @Translation("View Mode"),
 *   description = @Translation("Allows to choose a entity view more per each row")
 * )
 */
class ViewMode extends RowDisplayBase {

  public function build(EntityCollectionInterface $collection, TreeNodeInterface $item) {
    // TODO: Implement build() method.
  }

  /**
   * Sets the configuration for this plugin instance.
   *
   * @param array $configuration
   *   An associative array containing the plugin's configuration.
   */
  public function setConfiguration(array $configuration) {
    // TODO: Implement setConfiguration() method.
  }
}