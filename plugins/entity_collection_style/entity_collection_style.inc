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
    $plugin = ctools_get_plugins('entity_collection', 'plugins', $collection->renderer);
    return new $plugin['entity_collection_style']['class']();
  }
}