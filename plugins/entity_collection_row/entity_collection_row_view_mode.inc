<?php
class EntityCollectionRowViewMode extends EntityCollectionRow {
  public function getOptions($entity_type, $entity) {
    $entity_info = entity_get_info($entity_type);
    $options = array();
    foreach ($entity_info['view modes'] as $mode => $info) {
      $options[$mode] = $info['label'];
    }
    return $options;
  }

  public function build(EntityCollection $collection, EntityCollectionTreeNode $item, $langcode = NULL) {
    return entity_view($item->type, array($item->entity_id => $item->content), $item->view_mode, $langcode);
  }
}