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

}