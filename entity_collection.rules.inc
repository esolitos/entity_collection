<?php
/**
 * @file
 * Rules integration for entity collection.
 */

/**
 * Implements hook_rules_action_info().
 */
function entity_collection_rules_action_info() {
  $items['entity_collection_load_action'] = array(
    'label' => t('Load entity collection'),
    'parameter' => array(
      'name' => array(
        'type' => 'text',
        'label' => t('Entity collection'),
        'save' => TRUE,
        'options list' => 'entity_collection_get_names',
        'restriction' => 'input',
      ),
    ),
    'provides' => array(
      'loaded_collection' => array(
        'type' => 'entity_collection',
        'label' => t('Loaded collection'),
      ),
    ),
  	'group' => t('Entity collection'),
  );  
  return $items;
}

function entity_collection_get_names() {
  $items = array();
  foreach (entity_collection_load_all() as $name => $collection) {
    $items[$name] = $collection->title;
  }
  return $items;
}

/**
 * Callback for loading an entity collection.
 * @param string $name
 */
function entity_collection_load_action($name) {
  return array('loaded_collection' => entity_collection_load($name));
}
