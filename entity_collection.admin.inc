<?php

/**
 * @file
 * This file contains admin pages for the entity_collection module.
 */

/**
 * Form callback for the entity_collection add form.
 */
function entity_collection_add_form($form, &$form_state, $bundle) {
  $form = array();
  $form_state['bundle'] = $bundle;
  $form = entity_collection_form($form, $form_state);
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save entity_collection'),
    '#weight' => 40,
  );
  return $form;
}

/**
 * Form callback for the entity_collection edit form.
 */
function entity_collection_edit_form($form, &$form_state, $entity_collection) {
  $form = array();
  $form_state['entity_collection'] = $entity_collection;
  $form = entity_collection_form($form, $form_state);
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save block'),
    '#weight' => 40,
  );
  return $form;
}

function entity_collection_delete_form($form, &$form_state, $entity_collection) {
  $form_state['entity_collection'] = $entity_collection;
  return confirm_form($form, t('Are you sure you want to delete this block?'), 'admin/structure/entity_collection');
}

function entity_collection_delete_form_submit($form, &$form_state) {
  entity_delete('entity_collection', $form_state['entity_collection']->rid);
  $form_state['redirect'] = 'admin/structure/entity_collection';
}

/**
 * Form for creating entity_collections.
 * @param array $form
 * @param array $form_state
 *   The form state should contain at least the type of the entity block.
 * @return array the form for the entity block.
 */
function entity_collection_form($form = array(), &$form_state = array()) {
  if (empty($form_state['entity_collection'])) {
    $form_state['entity_collection'] = new EntityCollection;
    $form_state['entity_collection']->bundle = $form_state['bundle'];
  }
  else {
    _entity_collection_context_form($form, $form_state);
  }
  $entity_collection = $form_state['entity_collection'];
  $form['settings'] = array(
    '#tree' => TRUE,
  );
  $form['title'] = array(
    '#type' => 'textfield',
    '#required' => TRUE,
    '#default_value' => $entity_collection->title,
    '#title' => t('Title'),
  );
  // Machine-readable name.
  $form['name'] = array(
    '#type' => 'machine_name',
    '#default_value' => $entity_collection->name,
    '#disabled' => isset($entity_collection->name),
    '#required' => TRUE,
    '#machine_name' => array(
      'exists' => 'entity_collection_load',
      'source' => array('title'),
    ),
    '#description' => t('A unique machine-readable name for this block. It must only contain lowercase letters, numbers, and underscores.'),
  );
  $form['renderer'] = array(
    '#type' => 'select',
    '#title' => t('Style'),
    '#options' => entity_collection_get_renderer_options(),
    '#description' => t('Select the style you want to use for rendering this entity_collection here.')
  );
  field_attach_form('entity_collection', $entity_collection, $form, $form_state);
  $form['#validate'][] = 'entity_collection_form_validate';
  $form['#submit'][] = 'entity_collection_form_submit';
  return $form;
}

function _entity_collection_context_form(&$form, &$form_state) {
  ctools_include('context-admin');
  ctools_include('plugins-admin');
  ctools_include('cache');
  ctools_context_admin_includes();

  $entity_collection = $form_state['entity_collection'];
  if (!isset($entity_collection->settings)) {
    $entity_collection->settings = new stdClass;
    $entity_collection->requiredcontexts = array();
  }
  $cached_entity_collection = ctools_cache_get('entity_collection_cache_object', $entity_collection->name);
  if ($cached_entity_collection) {
    $form_state['entity_collection']->settings = $entity_collection->settings = $cached_entity_collection;
  }
  else {
    ctools_cache_set('entity_collection_cache_object', $entity_collection->name, $entity_collection->settings);
  }
  ctools_context_add_required_context_form('entity_collection_cache_object', $form['settings'], $form_state, $form['contexts']['required'], $entity_collection->settings, $entity_collection->name);
}

/**
 * Validate our form.
 * @param array $form
 * @param array $form_state
 */
function entity_collection_form_validate($form, &$form_state) {
  // Fetch the entity_collection and populate it. Since this is very simple to do,
  // let's just do it ourselves instead of using the entity api function.
  $entity_collection = &$form_state['entity_collection'];
  $entity_collection->name = $form_state['values']['name'];
  $entity_collection->title = $form_state['values']['title'];
  $entity_collection->renderer = $form_state['values']['renderer'];
  field_attach_form_validate('entity_collection', $entity_collection, $form, $form_state);
}

/**
 * Finally time to actually save the block.
 * @param type $form
 * @param array $form_state
 */
function entity_collection_form_submit($form, &$form_state) {
  ctools_include('cache');
  $entity_collection = $form_state['entity_collection'];
  ctools_cache_clear('entity_collection_cache_object', $entity_collection->name);
  field_attach_submit('entity_collection', $entity_collection, $form, $form_state);
  entity_save('entity_collection', $entity_collection);
  $form_state['redirect'] = 'admin/structure/entity_collection';
}

/**
 * An admin overview page.
 */
function entity_collection_admin() {
  // Get all entities.
  $entity_collections = entity_load('entity_collection');
  $entity_collection_table = array();
  $entity_collection = new EntityCollection;
  foreach ($entity_collections as $entity_collection) {
    $operations = l(t('Edit'), 'entity-collection/' . $entity_collection->name . '/edit');
    if (!$entity_collection->hasStatus(ENTITY_IN_CODE)) {
       $operations .= ' | ' . l(t('Delete'), 'entity-collection/' . $entity_collection->name . '/delete');
    }
    $operations .= l(' | ' . t('Manage content'), 'entity-collection/' . $entity_collection->name . '/content');
    $entity_collection_table[] = array(
      $entity_collection->title,
      $entity_collection->bundle,
      $operations,
    );
  }
  if (count($entity_collection_table)) {
    return theme('table', array('rows' => $entity_collection_table, 'header' => array('Title', 'Type', 'Operations')));
  }
  return t('You haven\'t created any entity_collections yet.');
}

/**
 * Manage the content of a entity_collection.
 * This is a simple and included admin-side form.
 */
function entity_collection_content_form($form, &$form_state, $entity_collection) {
  $form_state['entity_collection'] = $entity_collection;
  ctools_include('context');
  $contexts = array();
  if (isset($_GET['contexts'])) {
    $contexts = explode(':', $_GET['contexts']);
  }
  // Convert arguments to CTools Contexts.
  foreach ($contexts as $i => &$context) {
    $contexts[] = ctools_context_create($entity_collection->settings->requiredcontexts[$i]['name'], $context);
  }
  $tree = entity_collection_storage_factory($entity_collection->bundle)->retrieve($entity_collection->name, $contexts);
  $form_state['contexts'] = $contexts;
  $wrappers = array();
  $form['#tree'] = TRUE;
  $form['contexts'] = array(
    '#type' => 'fieldset',
    '#title' => t('Contexts'),
    '#collapsible' => TRUE,
  );
  entity_collection_content_form_content($entity_collection, $form, $tree);
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
    '#autocomplete_path' => 'entity_collection/autocomplete',
  );
  $form['add_content'] = array(
    '#type' => 'submit',
    '#value' => t('Add content'),
    '#submit' => array('entity_collection_content_form_add_content'),
  );
  return $form;
}

function entity_collection_content_form_content($entity_collection, &$form, $tree, $parent = 0, $depth = 0) {
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
      '#options' => _entity_collection_get_view_mode_options($info),
    );
    $form['content'][$key]['delete'] = array(
      '#type' => 'markup',
      '#markup' => l(t('Delete'), 'entity_collection/content/' . $entity_collection->name . '/delete/' . $child->entity_id),
    );
    $children = $child->getChildren();
    if (count($children)) {
      foreach($children as $child_node) {
        entity_collection_content_form_content($entity_collection, $form, $child_node, $child->key, $depth+1);
      }
    }
    $i++;
  }
}

function _entity_collection_get_view_mode_options($entity_info) {
  $options = array();
  foreach ($entity_info['view modes'] as $mode => $info) {
    $options[$mode] = $info['label'];
  }
  return $options;
}

function entity_collection_content_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $tree = entity_collection_content_build_tree($values['content']);
  if (isset($_GET['contexts'])) {
    $form_state['redirect'] = array(current_path(), array('query' => array('contexts' => $_GET['contexts'])));
  }
  entity_collection_storage_factory($form_state['entity_collection']->bundle)->save($form_state['entity_collection']->name, $tree, $form_state['contexts']);
}

/**
 * Build a tree from from a submitted form.
 */
function entity_collection_content_build_tree($values) {
  $tree = new EntityCollectionTreeNode();
  uasort($values, 'entity_collection_content_compare');
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

function entity_collection_content_compare($a, $b) {
  return $a['position'] > $b['position'];
}

/**
 * Theme function for the content form.
 * @param type $variables
 *   Variables containing the form to render.
 */
function theme_entity_collection_content_form(&$variables) {
  $form = &$variables['form'];
  $rows = array();
  foreach (element_children($form['content']) as $key) {
    $form['content'][$key]['parent']['#attributes']['class'] = array('entity_collection-parent');
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
    'attributes' => array('id' => 'entity_collection-table'),
  ));
  drupal_add_tabledrag('entity_collection-table', 'match', 'parent', 'entity_collection-parent', 'entity_collection-parent', 'key', FALSE);
  drupal_add_tabledrag('entity_collection-table', 'depth', 'group', 'depth', NULL, NULL, FALSE);
  drupal_add_tabledrag('entity_collection-table', 'order', 'sibling', 'position');
  return $output . drupal_render_children($variables['form']);
}

function entity_collection_content_form_autocomplete($string = '') {
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

function entity_collection_content_form_add_content($form, &$form_state) {
  $entity_collection = $form_state['entity_collection'];
  list($type, $value) = explode(':', $form_state['values']['content_select']);
  $entities = entity_load($type, array($value));
  if (isset($entities[$value])) {
    $entity = $entities[$value];
    $item = new EntityCollectionTreeNode($type, $value, $entity);
    if (isset($form_state['contexts'])) {
      $form_state['redirect'] = array(current_path(), array('query' => array('contexts' => $_GET['contexts'])));
    }
    entity_collection_storage_factory($entity_collection->bundle)->appendItem($entity_collection->name, $item, $form_state['contexts']);
  }
}
