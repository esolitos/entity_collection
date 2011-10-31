<?php

/**
 * Simple interface for storing entity_collections.
 */
interface EntityCollectionStorage {

  public function save(EntityCollection $collection, EntityCollectionTreeNode $tree, $contexts = array(), $position = 0);

  public function appendItem(EntityCollection $collection, EntityCollectionTreeNode $item, $contexts = array());

  public function retrieve(EntityCollection $collection, $contexts = NULL);

  public function delete(EntityCollection $collection, $entity_type, array $entries, $contexts = NULL);

  public function drop(EntityCollection $collection);

}

