<?php
class EntityCollectionRowPanelsMini extends EntityCollectionRow {
  public function getOptions($entity_type, $entity) {
    $mini_panels = panels_mini_load_all();
    $options = array();
    foreach ($mini_panels as $name => $mini_panel) {
      if (count($mini_panel->requiredcontexts) == 1 && $mini_panel->requiredcontexts[0]['name'] == 'entity:' . $entity_type) {
        $options[$name] = $mini_panel->admin_title;
      }
    }
    return $options;
  }

  public function build(EntityCollection $collection, EntityCollectionTreeNode $item, $langcode = NULL) {
    $panel = panels_mini_load($item->style);
    if (isset($panel)) {
      $contexts = array(ctools_context_create('entity:' . $item->type, $item->content));
      $context = ctools_context_match_required_contexts($panel->requiredcontexts, $contexts);
      $panel->context = $panel->display->context = ctools_context_load_contexts($panel, FALSE, $context);
      $panel->display->owner = $panel;
      $panel->display->owner->id = $panel->name;
      return array(
        '#markup' => panels_render_display($panel->display),
      );
    }
    return array();
  }
}