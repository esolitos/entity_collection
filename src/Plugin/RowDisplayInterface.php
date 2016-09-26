<?php

namespace Drupal\entity_collection\Plugin;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Defines an interface for Entity Collection: Row Display plugins.
 */
interface RowDisplayInterface extends EntityCollectionPluginBaseInterface {


  /**
   * Indicate if we allow one style per row or not.
   *
   * @return bool
   */
  public function useStylePerRow();

  /**
   * Build a row in an entity collection.
   *
   * @param EntityCollectionInterface $collection
   *   The entity collection in which the content is.
   * @param TreeNodeInterface $item
   *   The item to render.
   */
  public function build(EntityCollectionInterface $collection, TreeNodeInterface $item);

}
