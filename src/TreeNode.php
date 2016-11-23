<?php

namespace Drupal\entity_collection;


use Drupal\Core\Entity\EntityInterface;

class TreeNode implements TreeNodeInterface {

  protected $children;

  /**
   * Count elements of an object
   * @link http://php.net/manual/en/countable.count.php
   * @return int The custom count as an integer.
   * </p>
   * <p>
   * The return value is cast to an integer.
   * @since 5.1.0
   */
  public function count() {
    // TODO: Implement count() method.
  }

  /**
   * @param string $key
   * @param bool $deep_search
   * @return TreeNodeInterface
   */
  public function getChild($key, $deep_search = TRUE) {
    // TODO: Implement getChild() method.
  }

  public function getChildren() {
    // TODO: Implement getChildren() method.
  }

  public function addChild(TreeNodeInterface $child) {
    // TODO: Implement addChild() method.
  }

  public function removeChild(TreeNodeInterface $child) {
    // TODO: Implement removeChild() method.
  }

  public static function keyForNode(TreeNodeInterface $node) {
    // TODO: Implement keyForNode() method.
  }

  public static function keyForEntity(EntityInterface $entity) {
    // TODO: Implement keyForEntity() method.
  }

  public function setPosition($position) {
    // TODO: Implement setPosition() method.
  }

  public function getPosition() {
    // TODO: Implement getPosition() method.
  }
}