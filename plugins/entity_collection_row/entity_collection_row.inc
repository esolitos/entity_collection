<?php
/**
 * @file
 * Definition of the API for rows plugins.
 */

/**
 * Interface common to all row style plugins.
 */
interface EntityCollectionRowInterface {

  /**
   * Get options for all styles that can be chosen for this row.
   * @param string $entity_type
   * @param stdClass $entity
   */
  public function getOptions($entity_type, $entity);

  /**
   * Build a row in an entity collection.
   * @param EntityCollection $collection
   * 	The entity collection in which the content is.
   * @param EntityCollectionTreeNode $item
   * 	The item to render.
   * @param string $langcode
   *  The language code to use.
   */
  public function build(EntityCollection $collection, EntityCollectionTreeNode $item, $langcode = NULL);
}

/**
 * An abstract class implementing some of the row interface.
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
   * Get all row plugins.
   */
  public static function getRows() {
    ctools_include('plugins');
    $plugins = ctools_get_plugins('entity_collection', 'plugins');
    // Get all available storage backends.
    $row_plugins = array();
    foreach ($plugins as $name => $info) {
      if (isset($info['title']) && isset($info['entity_collection_row'])) {
        $row_plugins[$name] = $info;
      }
    }
    return $row_plugins;
  }

  /**
   * Get all row styles formatted for #options
   * @return array
   *   An array of styles.
   */
  public static function getRowOptions() {
    ctools_include('plugins');
    $plugins = EntityCollectionRow::getRows();
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
