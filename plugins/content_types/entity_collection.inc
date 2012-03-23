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
      'name' => '',
      'type' => '',
      'title' => '',
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
    $content->subject = $entity->title;
    if (module_exists('references_dialog')) {
      $content->content['#attached'] = references_dialog_attached();
      $content->content['#attached']['js'][] = references_dialog_js_settings($conf['name'], array('callback_path' => 'entity-collection/' . $entity->name . '/ajax/add', 'refresh' => TRUE));
    }
    $content->content['content'] = $entity->buildContent();
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
    if (module_exists('references_dialog')) {
      $content->admin_links = references_dialog_get_views_search_links($conf['name']);
      $content->admin_links[] = references_dialog_link(array('href' => 'node/add/ns-article', 'title' => t('Add article')));
    }
    $content->admin_links[] = array('href' => 'entity-collection/' . $entity->name . '/content', 'title' => t('Content'), 'query' => $query);
    $content->admin_links[] = array('href' => 'entity-collection/' . $entity->name . '/edit', 'title' => t('Edit'), 'query' => $query + drupal_get_destination());

  }
  return $content;
}

/**
 * Ajax callback for adding things into an entity collection.
 */
function entity_collection_ajax($entity_collection) {
  $data = $_POST['data'];
}

/**
 * The form to add or edit a node as content.
 */
function entity_collection_entity_collection_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];
  return $form;
}

/**
 * Returns the administrative title for a node.
 */
function entity_collection_entity_collection_content_type_admin_title($subtype, $conf) {
  return t('Entity collection: @var', array('@var' => $conf['name']));
}
