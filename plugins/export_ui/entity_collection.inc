<?php

$plugin = array(
  'schema' => 'entity_collection_bundles',
  'access' => 'administer entity_collection types',
  'create access' => 'create entity_collections',
  'menu' => array(
    'menu item' => 'entity_collection_bundle',
    'menu title' => 'Entity Collection types',
    'menu description' => 'Administer entity_collection types',
  ),
  'title singular' => t('Entity collection type'),
  'title plural' => t('Entity collection types'),
  'title singular proper' => t('Entity Collection type'),
  'title plural proper' => t('Entity Collection types'),
  'export' => array(
    'admin_title' => 'label',
  ),
  'form' => array(
     'settings' => 'entity_collection_export_ui_form',
     'submit' => 'entity_collection_export_ui_form_submit'
   ),
  'handler' => array(
    'class' => 'entity_collection_ui',
    'parent' => 'ctools_export_ui',
  ),
);

function entity_collection_export_ui_form(&$form, &$form_state) {
  $entity_collection = $form_state['item'];

  $form['storage'] = array(
    '#type' => 'select',
    '#title' => t('Storage'),
    '#default_value' => isset($entity_collection->storage) ? $entity_collection->storage : NULL,
    '#options' => EntityCollectionStorage::getStorageOptions(),
    '#required' => TRUE,
  );
  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#default_value' => $entity_collection->description,
  );
  return $form;
}

function entity_collection_export_ui_form_submit(&$form, &$form_state) {
  $form_state['item']->label = $form_state['values']['label'];
  $form_state['item']->storage = $form_state['values']['storage'];
  $form_state['item']->description = $form_state['values']['description'];
  menu_cache_clear_all();
}
