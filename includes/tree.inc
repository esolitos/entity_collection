<?php

class EntityCollectionTreeNode {
  public $type;
  public $entity_id;
  public $key;
  public $parent;
  public $root;
  public $content;
  public $view_mode;
  private $children = array();
  public $list = array();

  public function __construct($type = NULL, $entity_id = NULL, $content = NULL, $view_mode = NULL) {
    $this->type = $type;
    $this->root = $this;
    $this->entity_id = $entity_id;
    $this->content = $content;
    $this->view_mode = $view_mode;
  }

  /**
   * Add a child to this node. This is a helper for addChildNode.
   * @param string $name
   * @param stdClass $content
   */
  public function addChild($type, $entity_id, $content, $view_mode = NULL, $key = NULL) {
    $node = new EntityCollectionTreeNode($type, $entity_id, $content, $view_mode);
    if (isset($key)) {
      $node->key = $key;
    }
    $node->parent = $this;
    $this->addChildNode($node);
  }

  /**
   * Add a node as a child to this one.
   * @param EntityCollectionTreeNode $child
   */
  public function addChildNode(EntityCollectionTreeNode $child) {
    if (!isset($this->children)) {
      $this->children = array();
    }
    if (!isset($child->key)) {
      $child->key = $this->getLastChildKey();
    }
    $child->root = $this->root;
    $this->root->list[$child->key] = $child;
    $this->children[$child->key] = $child;
  }

  public function removeChild($name) {
    unset($this->children[$child->name]);
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
    return $this->children[count($this->children)-1];
  }

  public function getLastChildKey() {
    $keys = array_keys($this->children);
    if (count($keys)) {
      return $keys[count($keys)-1];
    }
    return -1;
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
