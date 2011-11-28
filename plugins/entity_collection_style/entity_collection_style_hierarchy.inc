<?php

/**
 * Simple interface for viewing hierarchies of entities.
 */
class EntityCollectionStyleHierarchy extends EntityCollectionStyle {

  public function build(EntityCollection $entity, EntityCollectionTreeNode $tree, $langcode = NULL) {
    $content = array();
    foreach ($tree->getChildren() as $key => $item) {
      $data = EntityCollectionRow::getRow($entity)->build($entity, $item);
      $content['entity_collection'][$key] = array(
        $data,
        '#weight' => $i,
      );
      $i++;
    }
    return $content;
  }
}

