<?php

/**
 * @file
 * Page callbacks for entity_collections.
 */

function entity_collection_page(EntityCollection $entity_collection) {
  // Return a basic entity_collection with the "full" view mode.
  return $entity_collection->buildContent('full');
}
