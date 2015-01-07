<?php
/**
 * @file
 * Sample entity_collection storage database implementation.
 */

/**
 * Entity collection storage backend using the database.
 */
class EntityCollectionStorageLimitDB extends EntityCollectionStorageDB {


  /**
   * Settings for this storage engine.
   */
  function bundleSettingsForm($bundle, &$form, &$form_state) {
    $form['store_entity'] = array(
      '#type' => 'checkbox',
      '#title' => t('Store full entity'),
      '#default_value' => isset($bundle->settings['store_entity']) ? $bundle->settings['store_entity'] : FALSE,
      '#description' => t('Store the full entity in the entity collection table.
       This is performant in some cases, but it also requires you to set up your
       cache invalidation settings.'),
    );

    $form['store_limit'] = array(
      '#type' => 'textfield',
      '#title' => t('Storage limit'),
      '#default_value' => isset($bundle->settings['store_limit']) ? $bundle->settings['store_limit'] : 50,
      '#description' => t('Maximum number of entities to store.'),
    );
  }

  /**
   * Append a new item to the entity_collection.
   * @param string $name
   * @param EntityCollectionTreeNode $item
   */
  public function appendItem(EntityCollection $collection, EntityCollectionTreeNode $item, $contexts = array()) {
    // Fetch the last position.
    $query = db_select('entity_collection_storage', 'rs');
    $query->addField('rs', 'position');
    $query->orderBy('position', 'DESC');
    $query->condition('parent', isset($item->parent) && isset($item->parent->entity_id) ? $item->parent->entity_id : 0);
    $position = $query->execute()->fetchField();
    if ($position > 0) {
      $position++;
    }

    $this->save($collection, $item, $contexts, -1);
    
    $settings = $this->getStorageSettings($collection->bundle);
    $last_query = db_select('entity_collection_storage', 'rs');
    $last_query->fields('rs', array('eid', 'entity_type'));
    $last_query->orderBy('position', 'ASC');
    $last_query->range($settings['store_limit'], 10000);
    $last_query->condition('name', $collection->name);
    $last_entry_result = $last_query->execute();

    foreach ($last_entry_result as $record) {
      $this->delete($collection, $record->entity_type, array($record->eid));
    }
  }


  private function getStorageSettings($name) {
    $query = db_select('entity_collection_bundles', 'cb');
    $query->fields('cb', array('settings'));
    $query->condition('name', $name);
    
    if($result = $query->execute()->fetchField()) {
      return  (@unserialize($string) !== false) ? unserialize($result) : $result;
    }

  }
}
