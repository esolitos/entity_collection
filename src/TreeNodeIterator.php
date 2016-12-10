<?php

namespace Drupal\entity_collection;


class TreeNodeIterator extends \RecursiveIteratorIterator {

  /**
   * Construct a TreeNodeIterator
   *
   * @param \Drupal\entity_collection\TreeNodeInterface $tree
   */
  public function __construct(TreeNodeInterface $tree) {
    parent::__construct(new \RecursiveIteratorIterator($tree), \RecursiveIteratorIterator::SELF_FIRST);
  }

}
