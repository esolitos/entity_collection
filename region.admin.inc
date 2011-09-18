<?php

/**
 * @file
 * This file contains admin pages for the region module.
 */

/**
 * Form callback for the region add form.
 */
function region_add_form($form, &$form_state, $bundle) {
  $form = array();
  $form_state['bundle'] = $bundle;
  $form = region_form($form, $form_state);
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save region'),
    '#weight' => 40,
  );
  return $form;
}

/**
 * Form callback for the region edit form.
 */
function region_edit_form($form, &$form_state, $region) {
  $form = array();
  $form_state['region'] = $region;
  $form = region_form($form, $form_state);
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save block'),
    '#weight' => 40,
  );
  return $form;
}

function region_delete_form($form, &$form_state, $region) {
  $form_state['region'] = $region;
  return confirm_form($form, t('Are you sure you want to delete this block?'), 'admin/structure/region');
}

function region_delete_form_submit($form, &$form_state) {
  entity_delete('region', $form_state['region']->bid);
  $form_state['redirect'] = 'admin/structure/region';
}

/**
 * Form for creating regions.
 * @param array $form
 * @param array $form_state
 *   The form state should contain at least the type of the entity block.
 * @return array the form for the entity block.
 */
function region_form($form = array(), &$form_state = array()) {
  // If the user has provided a block for us, then we should probably use
  // that. Otherwise, create a blank entity block.
  if (!isset($form_state['region'])) {
    $form_state['region'] = new Region;
    $form_state['region']->bundle = $form_state['bundle'];
  }
  $region = $form_state['region'];
  $form['title'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $region->title,
    '#title' => t('Title'),
  );
  // Machine-readable name.
  $form['name'] = array(
    '#type' => 'machine_name',
    '#default_value' => $region->name,
    '#disabled' => isset($region->name),
    '#required' => TRUE,
    '#machine_name' => array(
      'exists' => 'region_load',
      'source' => array('title'),
    ),
    '#description' => t('A unique machine-readable name for this block. It must only contain lowercase letters, numbers, and underscores.'),
  );
  field_attach_form('region', $region, $form, $form_state);
  $form['#validate'][] = 'region_form_validate';
  $form['#submit'][] = 'region_form_submit';
  return $form;
}

/**
 * Validate our form.
 * @param array $form
 * @param array $form_state
 */
function region_form_validate($form, &$form_state) {
  // Fetch the block and populate it. Since this is very simple to do,
  // let's just do it ourselves instead of using the entity api function.
  $region = &$form_state['region'];
  $region->name = $form_state['values']['name'];
  $region->title = $form_state['values']['title'];
  field_attach_form_validate('region', $region, $form, $form_state);
}

/**
 * Finally time to actually save the block.
 * @param type $form
 * @param array $form_state
 */
function region_form_submit($form, &$form_state) {
  $region = $form_state['region'];
  field_attach_submit('region', $region, $form, $form_state);
  entity_save('region', $region);
  $form_state['redirect'] = 'admin/structure/region';
}

/**
 * An admin overview page.
 */
function region_admin() {
  // Get all entities.
  $regions = entity_load('region');
  $region_table = array();
  $region = new Region;
  foreach ($regions as $region) {
    $operations = l(t('Edit'), 'admin/structure/region/edit/' . $region->name);
    if (!$region->hasStatus(ENTITY_IN_CODE)) {
       $operations .= ' | ' . l(t('Delete'), 'admin/structure/region/delete/' . $region->name);
    }
    $region_table[] = array(
      $region->title,
      $region->bundle,
      $operations,
    );
  }
  if (count($region_table)) {
    return theme('table', array('rows' => $region_table, 'header' => array('Title', 'Type', 'Operations')));
  }
  return t('You haven\'t created any regions yet.');
}
