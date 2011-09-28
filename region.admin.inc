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
  $form['renderer'] = array(
    '#type' => 'select',
    '#title' => t('Style'),
    '#options' => region_get_renderer_options(),
    '#description' => t('Select the style you want to use for rendering this region here.')
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
  // Fetch the region and populate it. Since this is very simple to do,
  // let's just do it ourselves instead of using the entity api function.
  $region = &$form_state['region'];
  $region->name = $form_state['values']['name'];
  $region->title = $form_state['values']['title'];
  $region->renderer = $form_state['values']['renderer'];
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
    $operations = l(t('Edit'), 'region/edit/' . $region->name);
    if (!$region->hasStatus(ENTITY_IN_CODE)) {
       $operations .= ' | ' . l(t('Delete'), 'region/delete/' . $region->name);
    }
    $operations .= l(' | ' . t('Manage content'), 'region/content/' . $region->name);
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

/**
 * Manage the content of a region.
 * This is a simple and included admin-side form.
 */
function region_content_form($form, &$form_state, $region) {
  $form_state['region'] = $region;
  $tree = region_load_content($region->bundle, $region->name);
  $wrappers = array();
  $form['#tree'] = TRUE;
  region_content_form_content($region, $form, $tree);
  $parent_options = array(0 => 'None');
  $form['order_save'] = array(
    '#type' => 'submit',
    '#value' => t('Save order'),
  );
  $form['content_select'] = array(
    '#type' => 'textfield',
    '#title' => t('Add content'),
    '#maxlength' => 60,
    '#description' => t('Type in the name of the content you want to add here.'),
    '#autocomplete_path' => 'region/autocomplete',
  );
  $form['add_content'] = array(
    '#type' => 'submit',
    '#value' => t('Add content'),
    '#submit' => array('region_content_form_add_content'),
  );
  return $form;
}

function region_content_form_content($region, &$form, $tree, $parent = 0, $depth = 0) {
  $nodes = $tree->getChildren();
  $wrappers  = array();
  if (isset($tree->entity_id)) {
    $tree->truncate();
    array_unshift($nodes, $tree);
  }
  foreach ($nodes as $key => $child) {
    $wrappers[$key] = entity_metadata_wrapper($child->type, $child->content);
    $parent_options[$child->entity_id] = $wrappers[$key]->label();
  }
  $i = 0;
  foreach ($nodes as $key => $child) {
    $entity = $child->content;
    $entity_type = $child->type;
    $info = $wrappers[$key]->entityInfo();
    $form['content'][$key]['#content'] = $child;
    $form['content'][$key]['key'] = array(
      '#type' => 'hidden',
      '#value' => $child->key,
    );
    $form['content'][$key]['entity_id'] = array(
      '#type' => 'value',
      '#value' => $child->entity_id,
    );
    $form['content'][$key]['type'] = array(
      '#type' => 'value',
      '#value' => $child->type,
    );
    $form['content'][$key]['title'] = array(
      '#markup' => check_plain($wrappers[$key]->label())
    );
    $form['content'][$key]['position'] = array(
      '#type' => 'textfield',
      '#title' => t('Position'),
      '#size' => 3,
      '#default_value' => $i,
    );
    $form['content'][$key]['parent'] = array(
      '#type' => 'hidden',
      '#default_value' => $parent,
    );
    $form['content'][$key]['depth'] = array(
      '#type' => 'hidden',
      '#default_value' => $depth,
    );
    $form['content'][$key]['view_mode'] = array(
      '#type' => 'select',
      '#default_value' => $child->view_mode,
      '#options' => _region_get_view_mode_options($info),
    );
    $form['content'][$key]['delete'] = array(
      '#type' => 'markup',
      '#markup' => l(t('Delete'), 'region/content/' . $region->name . '/delete/' . $child->entity_id),
    );
    $children = $child->getChildren();
    if (count($children)) {
      foreach($children as $child_node) {
        region_content_form_content($region, $form, $child_node, $child->key, $depth+1);
      }
    }
    $i++;
  }
}

function _region_get_view_mode_options($entity_info) {
  $options = array();
  foreach ($entity_info['view modes'] as $mode => $info) {
    $options[$mode] = $info['label'];
  }
  return $options;
}

function region_content_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $tree = region_content_build_tree($values['content']);
  region_storage_factory($form_state['region']->bundle)->save($form_state['region']->name, $tree);
}

/**
 * Build a tree from from a submitted form.
 */
function region_content_build_tree($values) {
  $tree = new RegionNode();
  uasort($values, 'region_content_compare');
  foreach ($values as $value) {
    if ($value['parent'] != 0 && $parent = $tree->getChild($value['parent'])) {
      $node = $parent;
    }
    else {
      $node = $tree;
    }
    $entity = current(entity_load($value['type'], array($value['entity_id'])));
    $node->addChild($value['type'], $value['entity_id'], $entity, $value['view_mode'], $value['key']);
    $previous_value = $value;
  }
  return $tree;
}

function region_content_compare($a, $b) {
  return $a['position'] > $b['position'];
}

/**
 * Theme function for the content form.
 * @param type $variables
 *   Variables containing the form to render.
 */
function theme_region_content_form(&$variables) {
  $form = &$variables['form'];
  $rows = array();
  foreach (element_children($form['content']) as $key) {
    $form['content'][$key]['parent']['#attributes']['class'] = array('region-parent');
    $form['content'][$key]['key']['#attributes']['class'] = array('key');
    $form['content'][$key]['depth']['#attributes']['class'] = array('depth');
    $form['content'][$key]['position']['#attributes']['class'] = array('position');
    $title = drupal_render($form['content'][$key]['title']);
    $delete = drupal_render($form['content'][$key]['delete']);
    $position = drupal_render($form['content'][$key]['position']);
    $view_mode = drupal_render($form['content'][$key]['view_mode']);
    $parent = drupal_render($form['content'][$key]['parent']);
    $depth = drupal_render($form['content'][$key]['depth']);
    $rendered_key = drupal_render($form['content'][$key]['key']);
    $indentation = $form['content'][$key]['depth']['#default_value'] > 0 ? theme('indentation', array('size' => $form['content'][$key]['depth']['#default_value'])) : '';
    $rows[] = array('class' => array('draggable'), 'data' => array($indentation . $title . $parent . $depth . $rendered_key, $position, $view_mode, $delete));
  }
  $header = array(t('Title'), t('Position'), t('View mode'), t('Operations'));
  $output = theme('table', array(
    'header' => $header,
    'rows' => $rows,
    'attributes' => array('id' => 'region-table'),
  ));
  drupal_add_tabledrag('region-table', 'match', 'parent', 'region-parent', 'region-parent', 'key', FALSE);
  drupal_add_tabledrag('region-table', 'depth', 'group', 'depth', NULL, NULL, FALSE);
  drupal_add_tabledrag('region-table', 'order', 'sibling', 'position');
  return $output . drupal_render_children($variables['form']);
}

function region_content_form_autocomplete($string = '') {
  // Let's limit this to nodes for now.
  // @todo support all entities.
  $matches = array();
  $result = db_select('node')
    ->fields('node', array('nid', 'title'))
    ->condition('title', db_like($string) . '%', 'LIKE')->range(0, 10)->execute();
  foreach ($result as $node) {
    $matches['node:' . $node->nid] = check_plain($node->title);
  }
  drupal_json_output($matches);
}

function region_content_form_add_content($form, &$form_state) {
  $region = $form_state['region'];
  list($type, $value) = explode(':', $form_state['values']['content_select']);
  $entities = entity_load($type, array($value));
  if (isset($entities[$value])) {
    $entity = $entities[$value];
    $item = new RegionNode($type, $value, $entity);
    region_storage_factory($region->bundle)->appendItem($region->name, $item);
  }
}
