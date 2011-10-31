<?php

/**
 * @file
 * Page callbacks for entity_collections.
 */

function entity_collection_page(EntityCollection $entity_collection) {
  // Return a basic entity_collection with the "full" view mode.
  $contexts = array();
  if (isset($_GET['contexts'])) {
    ctools_include('context');
    $contexts = explode(':', $_GET['contexts']);
    // Convert arguments to CTools Contexts.
    foreach ($contexts as $i => &$context) {
      $contexts[] = ctools_context_create($entity_collection->settings->requiredcontexts[$i]['name'], $context);
    }
    $entity_collection->setContexts($contexts);
  }
  drupal_set_title($entity_collection->title);
  $content = $entity_collection->buildContent('full');
  $content['#contextual_links'] = array(
    'entity-collection' => array('contextual-links', array($entity_collection->rid)),
  );
  return $content;
}
