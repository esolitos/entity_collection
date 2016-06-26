<?php
/**
 * Created by PhpStorm.
 * User: esolitos
 * Date: 26/06/16
 * Time: 13:25
 */

namespace Drupal\entity_collection\Plugin\EntityCollectionListStyle;


use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\EntityCollectionListStyleBase;
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
class FlatList extends EntityCollectionListStyleBase {

  /**
   * {@inheritdoc}
   */
  public function build(EntityCollectionInterface $collection, TreeNodeInterface &$tree) {
    // TODO: Implement build() method.
  }


}