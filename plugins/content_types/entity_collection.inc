<?php

/**
 * @file
 * Plugin to handle the 'node' content type which allows individual nodes
 * to be embedded into a panel.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t('Entity Collection'),
);

function entity_collection_entity_collection_content_type_content_types() {
  $types = array();
  $defaults = array(
    'description' => t('An entity collection.'),
    'category' => array(t('Entity collections'), -9),
    'defaults' => array(
      'name' => '',
      'type' => '',
      'title' => '',
    ),
  );
  // Get all entities.
  $entities = entity_collection_load_all();
  foreach ($entities as $entity) {
    $type = $defaults;
    $type['title'] = t('%block entity block', array('%block' => $entity->title));
    $type['defaults']['name'] = $entity->name;
    $type['defaults']['type'] = $entity->type;
    if (!empty($entity->settings->requiredcontexts)) {
      $type['required context'] = array();
      foreach ($entity->settings->requiredcontexts as $context) {
        $info = ctools_get_context($context['name']);
        // TODO: allow an optional setting
        $type['required context'][] = new ctools_context_required($context['identifier'], $info['context name']);
      }
    }
    $types[$entity->name] = $type;
  }
  return $types;
}

/**
 * Output function for the 'node' content type.
 *
 * Outputs a node based on the module and delta supplied in the configuration.
 */
function entity_collection_entity_collection_content_type_render($subtype, $conf, $panel_args, $contexts) {
  $content = new stdClass;
  $entity = entity_collection_load($conf['name']);
  $entity->setContexts($contexts);
  $content->subject = $entity->title;
  $content->content = $entity->buildContent();
  $query = array();
  $serialized = array();
  foreach ($contexts as $context) {
    $serialized[] = $context->argument;
  }
  $query = array(
    'contexts' => implode(':', $serialized),
  );
  $content->admin_links = array(
    array('href' => 'entity-collection/' . $entity->name . '/content', 'title' => t('Content'), 'query' => $query),
    array('href' => 'entity-collection/' . $entity->name . '/edit', 'title' => t('Edit'), 'query' => $query + drupal_get_destination()),
  );
  return $content;
}

/**
 * The form to add or edit a node as content.
 */
function entity_collection_entity_collection_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];
  return $form;
}

/**
 * Validate the node selection.
 */
function entity_collection_entity_collection_content_type_edit_form_validate(&$form, &$form_state) {

}

/**
 * Validate the node selection.
 */
function entity_collection_entity_collection_content_type_edit_form_submit($form, &$form_state) {
  //foreach (array('nid', 'links', 'leave_node_title', 'link_node_title', 'identifier', 'build_mode') as $key) {
  //  $form_state['conf'][$key] = $form_state['values'][$key];
  //}
}

/**
 * Returns the administrative title for a node.
 */
function entity_collection_entity_collection_content_type_admin_title($subtype, $conf) {
  return t('Entity collection: @var', array('@var' => $conf['name']));
}
