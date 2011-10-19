<?php

/**
 * Simple interface for storing entity_collections.
 */
interface EntityCollectionStorage {

  public function save($name, EntityCollectionTreeNode $tree, $position = 0);

  public function appendItem($name, EntityCollectionTreeNode $item);

  public function retrieve($name);

  public function delete($name, $entries);

  public function drop($name);

}

