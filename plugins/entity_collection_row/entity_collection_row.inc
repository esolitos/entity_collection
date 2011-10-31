<?php

interface EntityCollectionRowInterface {
  public function getOptions($entity_type, $entity);
  public function build(EntityCollection $collection, EntityCollectionTreeNode $item, $langcode = NULL);  
}
/**
* Simple interface for storing entity_collections.
*/
abstract class EntityCollectionRow implements EntityCollectionRowInterface {
  
  /**
   * Get the EntityCollectionRow style attached to this collection.
   * @param EntityCollection $collection
   * @return EntityCollectionRowInterface
   *   The row plugin to use in this collection.
   */
  public static function getRow(EntityCollection $collection) {
    ctools_include('plugins');
    $plugin = ctools_get_plugins('entity_collection', 'plugins', $collection->row);
    return new $plugin['entity_collection_row']['class']();
  }
  
 /**
  * Get all row styles formatted for #options
  * @return array
  *   An array of styles.
  */
  public static function getRowOptions() {
    ctools_include('plugins');
    $plugins = ctools_get_plugins('entity_collection', 'plugins');
    // Get all available storage backends.
    $options = array();
    foreach ($plugins as $name => $info) {
      if (isset($info['title']) && isset($info['entity_collection_row'])) {
        $options[$name] = $info['title'];
      }
    }
    return $options;
  }
}
