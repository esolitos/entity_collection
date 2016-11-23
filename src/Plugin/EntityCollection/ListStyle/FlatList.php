<?php

namespace Drupal\entity_collection\Plugin\EntityCollection\ListStyle;

use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\ListStyleBase;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Class FlatList
 *
 * @EntityCollectionListStyle(
 *   id = "flat_list",
 *   label = @Translation("Flat List (aka Queue)"),
 *   description = @Translation("Defines a very simple plugin to handle a linear list without depth.")
 * )
 */
class FlatList extends ListStyleBase {

  /**
   * {@inheritdoc}
   */
  public function build(EntityCollectionInterface $collection, TreeNodeInterface &$tree) {
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