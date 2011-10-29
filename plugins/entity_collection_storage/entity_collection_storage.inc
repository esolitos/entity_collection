<?php

/**
 * Simple interface for storing entity_collections.
 */
interface EntityCollectionStorage {

  public function save($name, EntityCollectionTreeNode $tree, $contexts = array(), $position = 0);

  public function appendItem($name, EntityCollectionTreeNode $item, $contexts = array());

  public function retrieve($name, $contexts = array());

  public function delete($name, $entries, $contexts = array());

  public function drop($name);

}

