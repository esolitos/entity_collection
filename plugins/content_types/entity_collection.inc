<?php
/**
 * @file
 * Plugin to handle the 'entity_collection' content type which allows entity
 * collections to be embedded into a panel.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Entity Collection'),
);

/**
 * Returns all available entity collections as a content type.
 */
function entity_collection_entity_collection_content_type_content_types() {
  $types = array();
  $defaults = array(
    'description' => t('An entity collection.'),
    'category' => array(t('Entity collections'), -9),
    'defaults' => array(
      'name' => NULL,
      'type' => NULL,
      'editable' => FALSE,
      'add_form' => FALSE,
      'length' => 0,
      'offset' => 0,
      'rendering' => 0,
    ),
  );
  // Get all entities.
  $entities = entity_collection_load_all();
  foreach ($entities as $entity) {
    $type = $defaults;
    $type['title'] = t('%entity entity collection', array('%entity' => $entity->title));
    $type['defaults']['name'] = $entity->name;
    $type['defaults']['type'] = $entity->type;
    if (!empty($entity->requiredcontexts)) {
      $type['required context'] = array();
      foreach ($entity->requiredcontexts as $context) {
        $info = ctools_get_context($context['name']);
        $type['required context'][] = new ctools_context_required($context['identifier'], $info['context name']);
      }
    }
    $types[$entity->name] = $type;
  }
  return $types;
}

/**
 * Render an entity collection and it's content.
 */
function entity_collection_entity_collection_content_type_render($subtype, $conf, $panel_args, $contexts) {
  $content = new stdClass();
  $entity = entity_collection_load($conf['name']);
  if (!empty($entity)) {
    $entity->setContexts($contexts);
    $query = array();
    $serialized = array();
    if (is_array($contexts)) {
      foreach ($contexts as $context) {
        $serialized[] = $context->argument;
      }
      $query = array(
        'contexts' => implode(':', $serialized),
      );
    }
    $content->subject = $entity->title;
    if (user_access("manage content in {$entity->type} entity collections")) {
      $query = array();
      $content->admin_links[] = array('href' => 'entity-collection/' . $entity->name . '/edit', 'title' => t('Edit'), 'query' => $query + drupal_get_destination());
      if (isset($_GET['edit-collection']) && $_GET['edit-collection'] == $entity->eid && !empty($conf['editable'])) {
        module_load_include('admin.inc', 'entity_collection');
        $content->content['edit']['#markup'] = l(t('Show content'), current_path());
        $content->content['content'] = drupal_get_form('entity_collection_content_form', $entity, current_path());
        if (!empty($conf['add_form']) && user_access("add content to {$entity->type} entity collections")) {
          $content->content['add_form'] = drupal_get_form('entity_collection_content_add_form', $entity, TRUE);
        }
      }
      if (!empty($conf['editable'])) {
        $content->admin_links[] = array('href' => current_path(), 'title' => t('Manage content'), 'query' => array('edit-collection' => $entity->eid));
      }
      else {
        $content->admin_links[] = array('href' => 'entity-collection/' . $entity->name . '/content', 'title' => t('Manage content'), 'query' => $query);
      }
      // Ugly hack to fix problems with contextual links.
      if (module_exists('contextual') && variable_get('theme_default', '') == 'bartik') {
        $content->content['break'] = array(
          '#markup' => '<br />',
        );
      }
    }
    if (!isset($content->content['content'])) {
      $content->content['content'] = $entity->buildContent('default', NULL, $conf);
    }
    if (user_access("add content to {$entity->type} entity collections")) {
      if(!$entity->getTree()->totalCount()) {
        $content->content['content'] = t('No content has been added yet');
      }
    }
  }
  return $content;
}

/**
 * The form to add or edit a node as content.
 */
function entity_collection_entity_collection_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];
  $form['editable'] = array(
    '#type' => 'checkbox',
    '#title' => t('Make this entity collection editable in place'),
    '#default_value' => !empty($conf['editable']),
  );
  $form['add_form'] = array(
    '#type' => 'checkbox',
    '#title' => t('Show form for adding content when editing'),
    '#dependency' => array(
      'edit-editable' => array(TRUE),
    ),
    '#default_value' => !empty($conf['add_form']),
  );
  $form['limit'] = array(
    '#type' => 'checkbox',
    '#title' => t('Display a specified number of items'),
    '#default_value' => $conf['length'] + $conf['offset'] != 0,
  );
  $form['limitation'] = array(
    '#type' => 'fieldset',
    '#dependency' => array(
      'edit-limit' => array(TRUE),
    ),
  );
  $form['limitation']['length'] = array(
    '#type' => 'textfield',
    '#title' => t('Items to display'),
    '#description' => t('The number of items to display. Enter 0 for no limit.'),
    '#default_value' => $conf['length'],
  );
  $form['limitation']['offset'] = array(
    '#type' => 'textfield',
    '#title' => t('Offset'),
    '#description' => t('The number of items to skip. For example, if this field is 3, the first 3 items will be skipped and not displayed.'),
    '#default_value' => $conf['offset'],
  );
  $form['minipanel'] = array(
    '#type' => 'checkbox',
    '#title' => t('Render items with a mini panel'),
    '#default_value' => !empty($conf['rendering']),
  );
  $form['rendering'] = array(
    '#type' => 'select',
    '#options' => array_merge(array(
      0 => t(' - None - '),
    ), entity_collection_entity_collection_all_rendering_options($conf)),
    '#default_value' => isset($conf['rendering']) ? $conf['rendering'] : array(),
    '#dependency' => array(
      'edit-minipanel' => array(TRUE),
    ),
  );
  return $form;
}

function entity_collection_entity_collection_content_type_edit_form_submit($form, &$form_state) {
  // Copy everything from our defaults.
  foreach (array_keys($form_state['subtype']['defaults']) as $key) {
    if (isset($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
  }
  // Reset limitation if limit isn't checked.
  if (empty($form_state['values']['limit'])) {
    $form_state['conf']['length'] = $form_state['subtype']['defaults']['length'];
    $form_state['conf']['offset'] = $form_state['subtype']['defaults']['offset'];
  }
  // Reset rendering if minipanel isn't checked.
  if (empty($form_state['values']['minipanel'])) {
    $form_state['conf']['rendering'] = $form_state['subtype']['defaults']['rendering'];
  }
}

/**
 * Returns the administrative title for a node.
 */
function entity_collection_entity_collection_content_type_admin_title($subtype, $conf) {
  return t('Entity collection: @var', array('@var' => $conf['name']));
}

/**
  * Get all available rendering options (mini-panels).
  */
function entity_collection_entity_collection_all_rendering_options($conf) {
  $entity_collection = entity_collection_load($conf['name']);
  foreach ($entity_collection->settings['allowed_bundles'] as $type => $bundles) {
    if (count($bundles)) {
      $available_contexts[] = 'entity:' . $type;
    }
  }
  $mini_panels = panels_mini_load_all();
  $available_contexts = array('entity_collection_item');
  if (isset($entity_collection)) {
    $available_contexts[] = 'entity:entity_collection';
  }
  foreach ($entity_collection->requiredcontexts as $context) {
    $available_contexts[] = $context['name'];
  }
  $options = array();

  foreach ($mini_panels as $name => $mini_panel) {
    if (count($mini_panel->requiredcontexts) >= 1) {
      $applicable = TRUE;
      foreach ($mini_panel->requiredcontexts as $requiredcontext) {
        if (!in_array($requiredcontext['name'], $available_contexts)) {
          $applicable = FALSE;
          break;
        }
      }
      $options[$name] = $mini_panel->admin_title;
    }
  }
  return $options;
}