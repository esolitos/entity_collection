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
  
  /**
   * Get a storage plugin for a specific bundle.
   * @param $bundle
   *   The bundle the storage should be in.
   * @return EntityCollectionStorageInterface
   *   A storage plugin object.
   */
  public static function getStorage($bundle) {
    ctools_include('plugins');
    $bundle = entity_collection_bundle_load($bundle);
    $plugin = ctools_get_plugins('entity_collection', 'plugins', $bundle->storage);
    return new $plugin['entity_collection_storage']['class']();
  }
  
  /**
   * Get all storage backends formatted for #options
   * @return array
   *   An array of storage backends.
   */
  public static function getStorageOptions() {
    $storage_plugins = ctools_get_plugins('entity_collection', 'plugins');
    // Get all available storage backends.
    $storage_options = array();
    foreach ($storage_plugins as $name => $info) {
      if (isset($info['title']) && isset($info['entity_collection_storage'])) {
        $storage_options[$name] = $info['title'];
      }
    }
    return $storage_options;
  }
}
