<?php

/**
 * @file
 * Sample entity_collection storage database implementation.
 */
class EntityCollectionStorageDB extends EntityCollectionStorage {

  public function save(EntityCollection $collection, EntityCollectionTreeNode $tree, $contexts = array(), $position = 0) {
    $record = $this->getRecord($collection, $tree, $position, $contexts);
    $primary_keys = array();
    if ($record) {
      if ($this->itemExists($collection, $record->eid, $record->entity_type, $contexts)) {
        $primary_keys = array('eid', 'name');
      }
      drupal_write_record('entity_collection_storage', $record, $primary_keys);
    }
    $i = 0;
    foreach ($tree->getChildren() as $node) {
      $this->save($collection, $node, $contexts, $i);
      $i++;
    }
  }
  
  /**
   * Settings for this storage engine.
   */
  function settingsForm(EntityCollection $collection, &$form, &$form_state) {
    $form['store_entity'] = array(
      '#type' => 'checkbox',
      '#title' => t('Store full entity'),
      '#default_value' => isset($collection->settings->store_entity) ? $collection->settings->store_entity : FALSE,
      '#description' => t('Store the full entity in the entity collection table.
       This is performant in some cases, but it also requires you to set up your
       cache invalidation settings.')
    );
  }

  /**
   * Append a new item to the entity_collection.
   * @param string $name
   * @param EntityCollectionTreeNode $item
   */
  public function appendItem(EntityCollection $collection, EntityCollectionTreeNode $item, $contexts = array()) {
    // Fetch the last position.
    $query = db_select('entity_collection_storage', 'rs');
    $query->addField('rs', 'position');
    $query->orderBy('position', 'DESC');
    $query->condition('parent', isset($item->parent) && isset($item->parent->entity_id) ? $item->parent->entity_id : 0);
    $position = $query->execute()->fetchField();
    if ($position > 0) {
      $position++;
    }
    $this->save($collection, $item, $contexts, $position);
  }

  private function getRecord(EntityCollection $collection, EntityCollectionTreeNode $item, $position = 0, $contexts = array()) {
    if (isset($item->content)) {
      $record = new stdClass();
      $record->name = $collection->name;
      if (!empty($collection->settings->store_entity)) {
        $record->entity = serialize($item->content);
      }
      $record->entity_type = $item->type;
      $record->eid = $item->entity_id;
      $record->view_mode = $item->view_mode;
      $record->parent = isset($item->parent) && isset($item->parent->key) ? $item->parent->key : 0;
      $record->position = $position;
      $record->contexts = $this->serializeContexts($contexts);
      return $record;
    }
    return FALSE;
  }

  private function itemExists(EntityCollection $collection, $entity_id, $type, $contexts = array()) {
    return db_select('entity_collection_storage', 'rs')
      ->fields('rs', array('eid'))
      ->condition('eid', $entity_id)
      ->condition('name', $collection->name)
      ->condition('contexts', $this->serializeContexts($contexts))
      ->execute()->fetchField();
  }

  /**
   * Retreive a entity_collection.
   * @param type $name
   */
  public function retrieve(EntityCollection $collection, $contexts = NULL) {
    // Serialize the context arguments into a string.
    $query = db_select('entity_collection_storage', 'rs')
      ->fields('rs')
      ->condition('name', $collection->name, '=')
      ->orderBy('parent')
      ->orderBy('position');
    if (isset($contexts)) {
      $query->condition('contexts', $this->serializeContexts($contexts));
    }
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
      if (!empty($collection->settings->store_entity)) {
        $entity = unserialize($value->entity);
      }
      // @todo Fix this so we only do one entity_load.
      else {
        $entity = entity_load_single($value->entity_type, $value->eid);
      }
      if ($entity) {
        $node->addChild($value->entity_type, $value->eid, $entity, $value->view_mode, $value->rsid);
      }
    }
    return $tree;
  }
  
  private function serializeContexts($contexts) {
    $serialized = array();
    foreach ($contexts as $context) {
      if (isset($context->argument)) {
        $serialized[] = $context->argument;
      }
    }
    return implode(':', $serialized);
  }

  public function delete(EntityCollection $collection, $entity_type, array $entries, $contexts = NULL) {
    $query = db_delete('entity_collection_storage')
      ->condition('name', $collection->name)
      ->condition('entity_type', $entity_type)
      ->condition('eid', $entries);
    if (isset($contexts)) {
      $query->condition('contexts', $this->serializeContexts($contexts));
    }
    $query->execute();
  }

  /**
   * Drop an entire entity_collection.
   * @param type $names
   */
  public function drop(EntityCollection $collection) {
    db_delete('entity_collection_storage')
      ->condition('name', $collection->name)
      ->execute();
  }
}
