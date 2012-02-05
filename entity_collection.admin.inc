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
    '#value' => t('Save'),
    '#weight' => 40,
  );
  return $form;
}

function entity_collection_delete_form($form, &$form_state, $entity_collection) {
  $form_state['entity_collection'] = $entity_collection;
  return confirm_form($form, t('Are you sure you want to delete this entity collection?'), 'admin/structure/entity_collection');
}

function entity_collection_delete_form_submit($form, &$form_state) {
  entity_delete('entity_collection', $form_state['entity_collection']->eid);
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
  $entity_info = entity_get_info();
  $entity_collection = $form_state['entity_collection'];
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
  $form['style'] = array(
    '#type' => 'select',
    '#title' => t('Style'),
    '#options' => EntityCollectionStyle::getStyleOptions(),
    '#description' => t('Select the style you want to use for rendering this entity_collection here.')
  );

  $form['row'] = array(
    '#type' => 'select',
    '#title' => t('Row'),
    '#options' => EntityCollectionRow::getRowOptions(),
    '#description' => t('Select the style you want to use for rendering this entity_collection here.')
  );
  field_attach_form('entity_collection', $entity_collection, $form, $form_state);
  $form['settings'] = array(
    '#tree' => TRUE,
  );
  $form['settings']['restrict'] = array(
    '#type' => 'checkbox',
    '#title' => t('Restrict content'),
    '#description' => t('Restrict the type of content in this collection to certain entity types and bundles.'),
    '#default_value' => isset($entity_collection->settings['restrict']) ? $entity_collection->settings['restrict'] : FALSE,
  );
  $form['settings']['allowed_bundles'] = array(
    '#type' => 'container',
    '#states' => array(
      'visible' => array(
       'input[name="settings[restrict]"]' => array('checked' => TRUE),
      ),
    ),
    '#tree' => TRUE,
  );
  foreach ($entity_info as $entity_name => $info) {
    $bundle_options = array();
    foreach ($info['bundles'] as $bundle_name => $bundle) {
      $bundle_options[$bundle_name] = $bundle['label'];
    }
    $form['settings']['allowed_bundles'][$entity_name] = array(
      '#type' => 'checkboxes',
      '#title' => $info['label'],
      '#options' => $bundle_options,
      '#default_value' => isset($entity_collection->settings['allowed_bundles'][$entity_name]) ?
      drupal_map_assoc($entity_collection->settings['allowed_bundles'][$entity_name]) : NULL,
    );
  }
  $storage = EntityCollectionStorage::getBundleStorage($entity_collection->bundle);
  if (method_exists($storage, 'entitySettingsForm')) {
    $storage->settingsForm($entity_collection, $form['settings'], $form_state);
  }
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
  if (!isset($entity_collection->requiredcontexts)) {
    $entity_collection->requiredcontexts = array();
  }
  $cached_entity_collection = ctools_cache_get('entity_collection_cache_object', $entity_collection->name);
  if ($cached_entity_collection) {
    $form_state['entity_collection'] = $entity_collection = $cached_entity_collection;
  }
  else {
    ctools_cache_set('entity_collection_cache_object', $entity_collection->name, $entity_collection);
  }
  ctools_context_add_required_context_form('entity_collection_cache_object',
    $form, $form_state, $form['contexts']['required'],
    $entity_collection, $entity_collection->name);
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
  $entity_collection->style = $form_state['values']['style'];
  $entity_collection->row = $form_state['values']['row'];
  field_attach_form_validate('entity_collection', $entity_collection, $form, $form_state);
}

/**
 * Save the actual entity collection.
 * @param type $form
 * @param array $form_state
 */
function entity_collection_form_submit($form, &$form_state) {
  ctools_include('cache');
  $entity_collection = $form_state['entity_collection'];
  $entity_collection->settings['restrict'] = $form_state['values']['settings']['restrict'];
  foreach ($form_state['values']['settings']['allowed_bundles'] as $entity_name => $allowed) {
    $entity_collection->settings['allowed_bundles'][$entity_name] = array();
    foreach ($allowed as $bundle => $allowed) {
      if ($allowed) {
        $entity_collection->settings['allowed_bundles'][$entity_name][] = $bundle;
      }
    }
  }
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
    $operations .= ' | ' . l(t('Manage content'), 'entity-collection/' . $entity_collection->name . '/content');
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
 * Content admin page callback.
 */
function entity_collection_content_admin($entity_collection) {
  $contexts = _entity_collection_get_contexts($entity_collection);
  if (count($contexts) != count($entity_collection->requiredcontexts)) {
    return drupal_get_form('entity_collection_context_input_form', $entity_collection);
  }
  else {
    return array(
      drupal_get_form('entity_collection_content_form', $entity_collection),
      drupal_get_form('entity_collection_content_add_form', $entity_collection),
    );
  }
}

/**
 * Manage the content of a entity_collection.
 * This is a simple and included admin-side form.
 */
function entity_collection_content_form($form, &$form_state, $entity_collection) {
  $form_state['entity_collection'] = $entity_collection;
  $contexts = $form_state['contexts'] = _entity_collection_get_contexts($entity_collection);
  $tree = EntityCollectionStorage::getBundleStorage($entity_collection->bundle)->retrieve($entity_collection, $contexts);
  $form['#tree'] = TRUE;
  entity_collection_content_form_content($entity_collection, $form, $tree);
  if (isset($form['content'])) {
    $form['order_save'] = array(
      '#type' => 'submit',
      '#value' => t('Save order'),
    );
  }
  if (isset($_GET['context'])) {
    $form['reference']['#ajax_parameters'] = array('contexts' => $_GET['contexts']);
  }
  return $form;
}

/**
 * Add an entity to an entity collection.
 * @param type $entity_type
 * @param type $entity
 */
function entity_collection_ajax_add($entity_collection) {
  $entity_id = check_plain($_POST['entity_id']);
  $entity_type = check_plain($_POST['entity_type']);
  $entity = entity_load_single($entity_type, $entity_id);
  if (isset($entity)) {
    EntityCollectionStorage::getBundleStorage($entity_collection->bundle)->appendItem($entity_collection, new EntityCollectionTreeNode($entity_type, $entity_id, $entity, 'default'));
  }
  return '';
}

function entity_collection_get_entity_type_options($entity_collection) {
  $info = entity_get_info();
  $options = array();
  foreach ($info as $name => $info) {
  if (empty($entity_collection->settings['restrict']) || !empty($entity_collection->settings['allowed_bundles'][$name]))
    $options[$name] = $info['label'];
  }
  return $options;
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
  }
  $i = 0;
  foreach ($nodes as $key => $child) {
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
      '#options' => EntityCollectionRow::getRow($entity_collection)->getOptions($child->type, $child->content),
    );
    $form['content'][$key]['delete'] = array(
      '#type' => 'markup',
      '#markup' => l(t('Delete'), 'entity-collection/' . $entity_collection->name . '/content/delete/' . $child->type . '/' . $child->entity_id, array('query' => array('destination' => current_path()))),
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
  EntityCollectionStorage::getBundleStorage($form_state['entity_collection']->bundle)->save($form_state['entity_collection'], $tree, $form_state['contexts']);
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
  $output = '';
  if (isset($form['content'])) {
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
  }
  return $output . drupal_render_children($variables['form']);
}

function entity_collection_content_delete_form($form, &$form_state, $entity_collection, $entity_type, $eid) {
  $form_state['entity_collection'] = $entity_collection;
  $form_state['entity_type'] = $entity_type;
  $form_state['eid'] = $eid;
  return confirm_form($form, t('Are you sure you want to delete this content from the entity collection?'), 'admin/structure/entity_collection');
}

function entity_collection_content_delete_form_submit($form, &$form_state) {
  EntityCollectionStorage::getBundleStorage($form_state['entity_collection']->bundle)->delete(
    $form_state['entity_collection'],
    $form_state['entity_type'],
    array($form_state['eid'])
  );
}

/**
 * A form for adding content to an entity collection.
 */
function entity_collection_content_add_form($form, &$form_state, $entity_collection) {
  $form_state['entity_collection'] = $entity_collection;
  $form['add_content'] = array(
    '#type' => 'fieldset',
    '#title' => t('Add content'),
  );
  $form['add_content']['entity_type'] = array(
    '#type' => 'select',
    '#options' => entity_collection_get_entity_type_options($entity_collection),
    '#title' => t('Entity type'),
    '#description' => t('Select the type of entity to search for.')
  );
  // We can't use the regular autocomplete, because we need to send the selected entity type
  // and the search.
  $form['add_content']['content_select'] = array(
    '#type' => 'textfield',
    '#title' => t('Search'),
    '#maxlength' => 60,
    '#description' => t('Type in the name of the content you want to add here.'),
    '#attributes' => array('class' => array('form-autocomplete')),
    '#attached' => array(
      'js' => array(
        drupal_get_path('module', 'entity_collection') . '/js/entity_autocomplete.js',
      ),
      'library' => array(
        array('system', 'drupal.autocomplete'),
      ),
    ),
  );
  $form['add_content']['reference'] = array(
    '#type' => 'references_dialog',
    '#format' => '$entity_type:$entity_id',
    '#attachable' => $entity_collection->name,
    '#target' => 'edit-content-select',
    '#operations' => array(),
  );
  $form['add_content']['add_content'] = array(
    '#type' => 'submit',
    '#value' => t('Add content'),
  );
  return $form;
}

function entity_collection_content_add_form_submit($form, &$form_state) {
  $entity_collection = $form_state['entity_collection'];
  $contexts = _entity_collection_get_contexts($entity_collection);
  list($type, $value) = explode(':', $form_state['values']['content_select']);
  $entities = entity_load($type, array($value));
  if (isset($entities[$value])) {
    $entity = $entities[$value];
    $item = new EntityCollectionTreeNode($type, $value, $entity);
    if (isset($_GET['contexts'])) {
      $form_state['redirect'] = array(current_path(), array('query' => array('contexts' => $_GET['contexts'])));
    }
    EntityCollectionStorage::getBundleStorage($entity_collection->bundle)
      ->appendItem($entity_collection, $item, $contexts);
  }
}

/**
 * Autocomplete for entity collection content form.
 * @param type $string
 */
function entity_collection_content_form_autocomplete($entity_type, $string = '') {
  // Let's limit this to nodes for now.
  $info = entity_get_info($entity_type);
  $label_property = $info['entity keys']['label'];
  $matches = array();
  $query = new EntityFieldQuery();
  $result = $query->entityCondition('entity_type', $entity_type)
    ->propertyCondition($label_property, db_like($string) . '%', 'LIKE')
    ->range(0, 10)
    ->execute();

  if (isset($result[$entity_type])) {
    $entities = entity_load($entity_type, array_keys($result[$entity_type]));
    foreach ($entities as $entity) {
      if (entity_access('view', $entity_type, $entity)) {
        list($id) = entity_extract_ids($entity_type, $entity);
        $matches[$entity_type . ':' . $id] = check_plain(entity_label($entity_type, $entity));
      }
    }
  }
  drupal_json_output($matches);
}
