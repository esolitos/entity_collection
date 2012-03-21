<?php

class EntityCollectionTreeNode {
  public $type;
  public $entity_id;
  public $key;
  public $parent;
  public $root;
  public $content;
  public $style;
  public $position = 0;
  private $children = array();
  public $list = array();

  public function __construct($type = NULL, $entity_id = NULL, $content = NULL, $style = NULL) {
    $this->type = $type;
    $this->root = $this;
    $this->entity_id = $entity_id;
    $this->content = $content;
    $this->style = $style;
  }

  /**
   * Add a child to this node. This is a helper for addChildNode.
   * @param string $name
   * @param stdClass $content
   */
  public function addChild($type, $entity_id, $content, $style = NULL, $key = NULL, $position = NULL) {
    $node = new EntityCollectionTreeNode($type, $entity_id, $content, $style);
    $node->position = isset($position) ? $position : count($this->children);
    $node->parent = $this;
    return $this->addChildNode($node);
  }

  /**
   * Add a node as a child to this node.
   * @param EntityCollectionTreeNode $child
   */
  public function addChildNode(EntityCollectionTreeNode $child) {
    if (!isset($this->children)) {
      $this->children = array();
    }
    $child->root = $this->root;
    $child->key = $key = $this->getNodeKey($child);
    $this->root->list[$key] = $child;
    $this->children[$key] = $child;
    return $child;
  }

  public function removeChild(EntityCollectionTreeNode $child) {
    $key = $this->getNodeKey($child);
    unset($this->root->list[$key]);
    unset($this->children[$key]);
  }

  public function getChild($key) {
    // Return the child if it exists.
    if (isset($this->children[$key])) {
      return $this->children[$key];
    }
    // Return FALSE if this branch has no children.
    if (!count($this->children)) {
      return FALSE;
    }
    foreach ($this->children as $node) {
      $result = $node->getChild($key);
      if (!empty($result)) {
        return $result;
      }
    }
    return FALSE;
  }

  public function getFlat() {
    return $this->list;
  }

  public function getLastChild() {
    return end($this->children);
  }
  /**
   * Get a child key based on entity type and entity id.
   */
  public function getChildKey($entity_type, $entity_id) {
    return $entity_type . ':' . $entity_id;
  }

  /**
   * Get a node key.
   */
  public function getNodeKey(EntityCollectionTreeNode $node) {
    return $node->type . ':' . $node->entity_id;
  }

  public function getLastChildKey() {
    return end(array_keys($this->children));
  }

  public function truncate() {
    $this->children = array();
  }

  public function getChildren() {
    return $this->children;
  }

  /**
   * Return this node as an array.
   */
  public function toArray() {
    $tree = array();
    foreach ($this->children as $key => $child) {
      $tree[$key]['content'] = $child->content;
      $tree[$key]['children'] = $child->toArray();
    }
    return $tree;
  }
}
