<?php

namespace Drupal\entity_collection;

use Drupal\Core\Entity\EntityInterface;

class TreeNode extends \ArrayIterator implements TreeNodeInterface {

  /**
   * @var TreeNodeInterface[]
   */
  private $children = [];

  /**
   * @inheritDoc
   */ static function create(EntityInterface $entity) {
    return new static([$entity]);
  }

  /**
   * Append an element
   * @param TreeNodeInterface $value
   */
  public function append($value) {
    if (!is_a($value, TreeNodeInterface::class)) {
      throw new \UnexpectedValueException("TreeNode can only contain other TreeNodes as children.");
    }

    parent::append($value);
  }

  /**
   * @inheritDoc
   */
  public function getChildren() {
    return new TreeNode($this->children);
  }

  /**
   * @inheritDoc
   */
  public function hasChildren() {
    return !empty($this->children);
  }

  /**
   * @inheritDoc
   */
  public function addChild(TreeNodeInterface $child) {
    $this->children[] = $child;
  }
}
