<?php

namespace Drupal\entity_collection\CollectionTree;

use Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodeChildException;

class TreeChildren extends \SplMinHeap {

  /**
   * @return \Drupal\entity_collection\CollectionTree\TreeNodeInterface
   */
  public function current() {
    if (is_a($c = parent::current(), TreeNodeInterface::class)) {
      return $c;
    }

    return NULL;
  }

  /**
   * @return \Drupal\entity_collection\CollectionTree\TreeNodeInterface
   */
  public function extract() {
    if (is_a($c = parent::extract(), TreeNodeInterface::class)) {
      return $c;
    }

    return NULL;
  }

  /**
   * @param $value \Drupal\entity_collection\CollectionTree\TreeNodeInterface
   * @throws \Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodeChildException
   */
  public function insert($value) {
    if (!is_a($value, TreeNodeInterface::class)) {
      throw new InvalidTreeNodeChildException("Only TreeNodeInterface are allowed." );
    }

    parent::insert($value);
  }

  /**
   * @inheritDoc
   */
  public function top() {
    if (is_a($t = parent::top(), TreeNodeInterface::class)) {
      return $t;
    }

    return NULL;
  }

}