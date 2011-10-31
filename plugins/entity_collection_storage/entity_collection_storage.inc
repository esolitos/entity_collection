<?php

/**
 * Simple interface for storing entity_collections.
 */
interface EntityCollectionStorageInterface {

  public function save(EntityCollection $collection, EntityCollectionTreeNode $tree, $contexts = array(), $position = 0);

  public function appendItem(EntityCollection $collection, EntityCollectionTreeNode $item, $contexts = array());

  public function retrieve(EntityCollection $collection, $contexts = NULL);

  public function delete(EntityCollection $collection, $entity_type, array $entries, $contexts = NULL);

  public function drop(EntityCollection $collection);

}

abstract class EntityCollectionStorage implements EntityCollectionStorageInterface {
  
  public static function getStorage($bundle) {
    ctools_include('plugins');
    $bundle = entity_collection_bundle_load($bundle);
    $plugin = ctools_get_plugins('entity_collection', 'plugins', $bundle->storage);
    return new $plugin['entity_collection_storage']['class']();
  }
}
