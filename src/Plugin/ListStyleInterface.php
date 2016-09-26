<?php

namespace Drupal\entity_collection\Plugin;
use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Defines an interface for Entity Collection: List Style plugins.
 */
interface ListStyleInterface extends EntityCollectionPluginBaseInterface {

  /**
   * Get the max depth that this style allows.
   *
   * @return int
   *   An integer value indicating the depth.
   */
  public function getMaxDepth();

  /**
   * Indicate if this style will use reordering.
   *
   * @return bool
   */
  public function useReordering();

  /**
   * Build the list for the given entity collection.
   *
   * @param EntityCollectionInterface $collection
   *   The entity in which the content belongs.
   * @param TreeNodeInterface $tree
   *   The tree to build, passed for reference in case some persistance is needed.
   */
  public function build(EntityCollectionInterface $collection, TreeNodeInterface &$tree);

}
