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
  $form['label'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $entity_collection->label,
    '#required' => TRUE,
  );
  $form['storage'] = array(
    '#type' => 'select',
    '#title' => t('Storage'),
    '#default_value' => isset($entity_collection->storage) ? $entity_collection->storage : NULL,
    '#options' => entity_collection_get_storage_options(),
    '#required' => TRUE,
  );
  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#description' => t('The human readable name or description of this preset.'),
    '#default_value' => $entity_collection->description,
    '#required' => TRUE,
  );
  return $form;
}

function entity_collection_export_ui_form_submit(&$form, &$form_state) {
  $form_state['item']->label = $form_state['values']['label'];
  $form_state['item']->storage = $form_state['values']['storage'];
  $form_state['item']->description = $form_state['values']['description'];
}
