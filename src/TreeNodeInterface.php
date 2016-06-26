<?php

namespace Drupal\entity_collection;


use Drupal\Core\Entity\EntityInterface;

interface TreeNodeInterface extends \Countable {

//  public function addChild($type, $entity_id, $content, $style = NULL, $key = NULL, $position = NULL, $locked = FALSE);

  /**
   * @param string $key
   * @param bool $deep_search
   * @return TreeNodeInterface
   */
  public function getChild($key, $deep_search = TRUE);

  public function getChildren();

  public function addChild(TreeNodeInterface $child);

  public function removeChild(TreeNodeInterface $child);

  public static function keyForNode(TreeNodeInterface $node);

  
  public static function keyForEntity(EntityInterface $entity);


  public function setPosition($position);

  public function getPosition();

//  public function lockPosition($lock = TRUE)
//  public function isPositionLocked();


//  public function getFlat($offset = 0, $length = NULL);

//  public function splice($offset = 0, $length = NULL);

//  public function getFirstChild();
//  public function getLastChild();


//  public function getChildKey($entity_type, $entity_id)


//  public function getLastChildKey();

//  public function truncate()

//  public function setParent(TreeNodeInterface $parent)

}