<?php

/**
 * @file
 * Sample entity_collection storage database implementation.
 */
class EntityCollectionStorageDB implements EntityCollectionStorage {

  public function save($name, EntityCollectionTreeNode $tree, $position = 0) {
    $record = $this->getRecord($name, $tree, $position);
    $primary_keys = array();
    if ($record) {
      if ($this->itemExists($record->name, $record->eid)) {
        $primary_keys = array('eid', 'name');
      }
      drupal_write_record('entity_collection_storage', $record, $primary_keys);
    }
    $i = 0;
    foreach ($tree->getChildren() as $node) {
      $this->save($name, $node, $i);
      $i++;
    }
  }

  /**
   * Append a new item to the entity_collection.
   * @param string $name
   * @param EntityCollectionTreeNode $item
   */
  public function appendItem($name, EntityCollectionTreeNode $item) {
    // Fetch the last position.
    $query = db_select('entity_collection_storage', 'rs');
    $query->addField('rs', 'position');
    $query->orderBy('position', 'DESC');
    $query->condition('parent', isset($item->parent) && isset($item->parent->entity_id) ? $item->parent->entity_id : 0);
    $position = $query->execute()->fetchField();
    if ($position > 0) {
      $position++;
    }
    $this->save($name, $item, $position);
  }

  private function getRecord($name, EntityCollectionTreeNode $item, $position = 0) {
    if (isset($item->content)) {
      $record = new stdClass();
      $record->name = $name;
      $record->entity = serialize($item->content);
      $record->entity_type = $item->type;
      $record->eid = $item->entity_id;
      $record->view_mode = $item->view_mode;
      $record->parent = isset($item->parent) && isset($item->parent->key) ? $item->parent->key : 0;
      $record->position = $position;
      return $record;
    }
    return FALSE;
  }

  private function itemExists($name, $entity_id) {
    return db_select('entity_collection_storage', 'rs')
      ->fields('rs', array('eid'))
      ->condition('eid', $entity_id)
      ->condition('name', $name)
      ->execute()->fetchField();
  }

  /**
   * Retreive a entity_collection.
   * @param type $name
   */
  public function retrieve($name) {
    $query = db_select('entity_collection_storage', 'rs')
      ->fields('rs')
      ->condition('name', $name, '=')
      ->orderBy('parent')
      ->orderBy('position');
    $result = $query->execute();
    $values = array();
    $tree = new EntityCollectionTreeNode();
    foreach ($result as $value) {
      if ($value->parent > 0) {
        $node = $tree->getChild($value->parent);
      }
      if (empty($node)) {
        $node = $tree;
      }
      $node->addChild($value->entity_type, $value->eid, unserialize($value->entity), $value->view_mode, $value->rsid);
    }
    return $tree;
  }

  public function delete($name, $entries) {
    db_delete('entity_collection_storage')
      ->condition('name', $name)
      ->condition('eid', array_keys($entries))
      ->execute();
  }

  /**
   * Drop an entire entity_collection.
   * @param type $names
   */
  public function drop($name) {

  }
}
