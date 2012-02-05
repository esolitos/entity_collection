<?php

/**
 * @file
 *
 * Plugin to provide a node context. A node context is a node wrapped in a
 * context object that can be utilized by anything that accepts contexts.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t("Entity Collection form"),
  'description' => t('Add separate parts from the entity collection form.'),
  'context' => 'entity_collection_content_form_context_create',

  'keyword' => 'entity_collection_form',
  'context name' => 'entity_collection_form',

  'get child' => 'entity_collection_content_form_context_get_child',
  'get children' => 'entity_collection_content_form_context_get_children',
);

function entity_collection_content_form_context_get_child($plugin, $parent, $child) {
  $entity_collection = entity_collection_load($child);
  if (!$entity_collection) {
    return;
  }
  return _entity_collection_content_form_context_info($entity_collection);
}

function _entity_collection_content_form_context_info($entity_collection) {
  $info = array(
    'name' => 'entity_collection_form:' . $entity_collection,
    'title' => t('!title entity collection content form', array('!title' => $entity_collection->title)),
    'description' => t('Use parts from the entity collection form.'),
    'keyword' => 'entity_collection_form',
    'context' => 'entity_collection_content_form_context_create',
    'context name' => 'entity_collection_form:' . $entity_collection->name,
    'entity collection' => $entity_collection->name
  );
  if (!empty($entity_collection->requiredcontexts)) {
    $info['required context'] = array();
    foreach ($entity_collection->requiredcontexts as $context) {
      $context_info = ctools_get_context($context['name']);
      // TODO: allow an optional setting
      $info['required context'][] = new ctools_context_required($context['identifier'], $context_info['context name']);
    }
  }
  return $info;
}

function entity_collection_content_form_context_get_children($plugin, $parent) {
  $types = array();
  $collections = entity_collection_load_all();
  foreach ($collections as $entity_collection) {
    $info = _entity_collection_content_form_context_info($entity_collection);
    $types[$info['name']] = $info;
  }
  return $types;
}

function entity_collection_content_form_context_create($empty, $data = NULL, $conf = FALSE, $plugin = array()) {
  $context = new ctools_context('entity_collection_form');
  $context->plugin = 'entity_collection';

  if ($empty) {
    return $context;
  }

  if (!empty($plugin['view name'])) {
    $data = entity_collection_load($plugin['entity collection']);
  }
  if (is_object($data)) {
    $context->data = $data;
    $context->title    = check_plain($data->title);
    $context->argument = $data->name;
    return $context;
  }
}
