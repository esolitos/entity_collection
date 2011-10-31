<?php

interface EntityCollectionStyleInterface {
  public function getMaxDepth();
  public function useGrouping();
  public function useReordering();
  public function build(EntityCollection $entity, EntityCollectionTreeNode $tree, $langcode = NULL);  
}
/**
* Simple interface for storing entity_collections.
*/
abstract class EntityCollectionStyle {
  public function getMaxDepth() {
    return 0;
  }

  public function useGrouping() {
    return FALSE;
  }

  public function useReordering() {
    return TRUE;
  }

  public static function getStyle(EntityCollection $collection) {
    ctools_include('plugins');
    $plugin = ctools_get_plugins('entity_collection', 'plugins', $collection->style);
    return new $plugin['entity_collection_style']['class']();
  }
  
  /**
   * Get all styles formatted for #options
   * @return array
   *   An array of storage backends.
   */
  public static function getStyleOptions() {
    ctools_include('plugins');
    $plugins = ctools_get_plugins('entity_collection', 'plugins');
    // Get all available storage backends.
    $options = array();
    foreach ($plugins as $name => $info) {
      if (isset($info['title']) && isset($info['entity_collection_style'])) {
        $options[$name] = $info['title'];
      }
    }
    return $options;
  }
}