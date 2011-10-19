<?php

/**
 * Simple interface for storing entity_collections.
 */
class EntityCollectionRendererFlat {
  public function getMaxDepth() {
    return 0;
  }

  public function useGrouping() {
    return FALSE;
  }

  public function useReordering() {
    return TRUE;
  }

  public function build(EntityCollectionTreeNode $tree, $langcode = NULL) {
    $content = array();
    // Get a flat list of everything. We don't care about
    // grouping, we just want a flat array, sorted properly.
    $list = $tree->getFlat();
    $i = 0;
    foreach ($list as $item) {
      $data = entity_view($item->type, array($item->entity_id => $item->content), $item->view_mode, $langcode);
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

