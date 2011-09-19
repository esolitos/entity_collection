<?php

$plugin = array(
  'schema' => 'region_bundles',
  'access' => 'administer region types',
  'create access' => 'create regions',
  'menu' => array(
    'menu item' => 'region_bundle',
    'menu title' => 'Region types',
    'menu description' => 'Administer region types',
  ),
  'title singular' => t('region type'),
  'title plural' => t('region types'),
  'title singular proper' => t('Region type'),
  'title plural proper' => t('Region types'),
  'form' => array(
     'settings' => 'region_export_ui_form',
     'submit' => 'region_export_ui_form_submit'
   ),
  'handler' => array(
    'class' => 'region_ui',
    'parent' => 'ctools_export_ui',
  ),
);

function region_export_ui_form(&$form, &$form_state) {
  $region = $form_state['item'];
  $form['label'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $region->label,
    '#required' => TRUE,
  );
  $form['storage'] = array(
    '#type' => 'select',
    '#title' => t('Storage'),
    '#default_value' => isset($region->storage) ? $region->storage : NULL,
    '#options' => region_get_storage_options(),
    '#required' => TRUE,
  );
  $form['description'] = array(
    '#type' => 'textarea',
    '#title' => t('Description'),
    '#description' => t('The human readable name or description of this preset.'),
    '#default_value' => $region->description,
    '#required' => TRUE,
  );
  return $form;
}

function region_export_ui_form_submit(&$form, &$form_state) {
  $form_state['item']->label = $form_state['values']['label'];
  $form_state['item']->storage = $form_state['values']['storage'];
  $form_state['item']->description = $form_state['values']['description'];
}
