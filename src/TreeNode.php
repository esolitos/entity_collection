<?php

namespace Drupal\entity_collection;

use Drupal\Core\Entity\EntityInterface;

class TreeNode extends \RecursiveIteratorIterator implements TreeNodeInterface {

  /**
   * @var array
   */
  private $children = [];

  /**
   * Construct a RecursiveIteratorIterator
   */
  public function __construct(\RecursiveArrayIterator $element = NULL) {
    if (empty($element)) {
      $element = new \RecursiveArrayIterator([]);
    }

    parent::__construct($element, \RecursiveIteratorIterator::SELF_FIRST);
  }

  /**
   * Given an entity it statically Creates a TreeNode
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity The Drupal entity to wrap
   *
   * @return \Drupal\entity_collection\TreeNodeInterface
   */
  public static function create(EntityInterface $entity) {
    $iterator = new \RecursiveArrayIterator([$entity]);

    return new static($iterator);
  }


  /**
   * Appends a TreeNode as children
   *
   * @param \Drupal\entity_collection\TreeNodeInterface $child
   *
   * @return mixed
   */
  public function addChild(TreeNodeInterface $child) {
    // TODO: Implement removeChild() method.
  }

  /**
   * Detaches a TreeNode from the tree and returns it.
   *
   * @param \Drupal\entity_collection\TreeNodeInterface $child
   * @return TreeNodeInterface
   */
  public function removeChild(TreeNodeInterface $child) {
    // TODO: Implement removeChild() method.
  }


}
