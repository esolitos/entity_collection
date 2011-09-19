<?php

/**
 * @file
 * Sample region storage database implementation.
 */
class RegionStorageDatabase implements RegionStorage {

  public function save($name, RegionNode $tree, $position = 0) {
    $record = $this->getRecord($name, $tree, $position);
    if ($record) {
      drupal_write_record('region_storage', $record);
    }
    $i = 0;
    foreach ($tree->getChildren() as $node) {
      $this->save($name, $tree, $i);
      $i++;
    }
  }

  /**
   * Append a new item to the region.
   * @param string $name
   * @param RegionNode $item
   */
  public function appendItem($name, RegionNode $item) {
    // Fetch the last position.
    $query = db_select('region_storage', 'rs');
    $query->addField('rs', 'position');
    $query->orderBy('position', 'DESC');
    $query->condition('parent', isset($item->parent) && isset($item->parent->entity_id) ? $item->parent->entity_id : 0);
    $position = $query->execute()->fetchField();
    if ($position != 0) {
      $position++;
    }
    $this->save($name, $item, $position);
  }

  private function getRecord($name, RegionNode $item, $position = 0) {
    if (isset($item->content)) {
      $record = new stdClass();
      $record->name = $name;
      $record->entity = serialize($item->content);
      $record->entity_type = $item->type;
      $record->eid = $item->entity_id;
      $record->parent = isset($item->parent) && isset($item->parent->content) ? $item->parent->entity_id : 0;
      $record->position = $position;
      return $record;
    }
    return FALSE;
  }

  /**
   * Retreive a region.
   * @param type $name
   */
  public function retrieve($name) {
    $query = db_select('region_storage', 'rs')
      ->fields('rs')
      ->condition('name', $name, '=')
      ->orderBy('parent')
      ->orderBy('position');
    $result = $query->execute();
    $values = array();
    $tree = new RegionNode();
    foreach ($result as $value) {
      $tree->addChild($value->entity_type, $value->eid, unserialize($value->entity));
    }
    return $tree;
  }

  public function delete($name, $entries) {
    db_delete('region_storage')
      ->condition('name', $name)
      ->condition('eid', array_keys($entries))
      ->execute();
  }

  /**
   * Drop an entire region.
   * @param type $names
   */
  public function drop($name) {

  }
}
