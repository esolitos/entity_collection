<?php

namespace Drupal\entity_collection;

use Drupal\Core\Entity\EntityInterface;

interface TreeNodeInterface extends \RecursiveIterator {

  /**
   * Given an entity it statically Creates a TreeNode
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity The Drupal entity to wrap
   *
   * @return \Drupal\entity_collection\TreeNodeInterface
   */
   public static function create(EntityInterface $entity);

  /**
   * Appends a TreeNode as children
   *
   * @param \Drupal\entity_collection\TreeNodeInterface $child
   * @return mixed
   */
  public function addChild(TreeNodeInterface $child);
}
