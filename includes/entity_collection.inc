<?php

/**
 * This class is used to represent a Region.
 */
class EntityCollection extends Entity {
  // We provide the values here for reference.
  public $type;
  public $name;
  public $bid;
  public $title;
  public $tree;
  public $cache;
  public $requiredcontexts = array();
  public $contexts = array();

  public function __construct($values = array()) {
    parent::__construct($values, 'entity_collection');
    $this->tree = new EntityCollectionTreeNode();
  }

  /**
   * Gets the type entity.
   *
   * @return Entity
   *  The type associated with this entity.
   */
  public function getTypeEntity() {
    if (isset($this->type)) {
      return entity_get_types($this->type);
    }
  }
  
  /**
   * Set the current contexts.
   */
  public function setContexts($contexts) {
    $this->contexts = $contexts;
  }

  /**
   * Build content.
   * @param string $view_mode
   *   The current view mode.
   * @param type $langcode
   *   The language code to use.
   * @return array
   *  An array that can be used with Drupals rendering system.
   */
  public function buildContent($view_mode = 'default', $langcode = NULL) {
    $tree = entity_collection_storage_factory($this->bundle)->retrieve($this, $this->contexts);
    $content = entity_collection_renderer_factory($this)->build($tree);
    $rendered = entity_get_controller($this->entityType)->buildContent($this, $view_mode, $langcode, $content);
    $rendered['#entity_collection'] = $this;
    $rendered['#theme'] = 'entity_collection';
    $rendered['#contextual_links']['entity_collection'] = array(
      'entity-collection', 
      array($this->name)
    );
    return $rendered;
  }

  public function getTree() {
    return $this->tree;
  }
}
