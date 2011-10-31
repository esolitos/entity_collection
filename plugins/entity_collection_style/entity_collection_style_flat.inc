<?php

/**
 * Simple interface for storing entity_collections.
 */
class EntityCollectionStyleFlat extends EntityCollectionStyle {

  public function build(EntityCollection $entity, EntityCollectionTreeNode $tree, $langcode = NULL) {
    $content = array();
    // Get a flat list of everything. We don't care about
    // grouping, we just want a flat array, sorted properly.
    $list = $tree->getFlat();
    $i = 0;
    foreach ($list as $item) {
      $data = EntityCollectionRow::getRow($entity)->build($entity, $item);
      // @todo, this sucks really bad...
      $content['entity_collection'][$item->entity_id] = array(
          $data['nodes'][$item->entity_id],
          '#weight' => $i,
      );
      $i++;
    }
    return $content;
  }
}

