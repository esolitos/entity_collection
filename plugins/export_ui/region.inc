<?php

$plugin = array(
  'schema' => 'region_bundles',
  'access' => 'administer region types',
  'create access' => 'create regions',
  'menu' => array(
    'menu item' => 'regions',
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
);

function region_export_ui_form(&$form, &$form_state) {
  $region = $form_state['item'];
  $form['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#default_value' => $region->title,
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
  $form_state['item']->title = $form_state['values']['title'];
  $form_state['item']->description = $form_state['values']['description'];
}
