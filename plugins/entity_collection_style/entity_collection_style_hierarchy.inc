<?php
/**
 * @file
 * A hiearchy style plugin.
 */

/**
 * This style can show groups of content in hierarchies.
 */
class EntityCollectionStyleHierarchy extends EntityCollectionStyle {

  /**
   * Build the hierarchy.
   * @see EntityCollectionStyle::build().
   */
  public function build(EntityCollection $entity, EntityCollectionTreeNode $tree, $langcode = NULL) {
    $content = array();
    $this->renderTreeNode($content, $entity, $tree, $langcode);
    return $content;
  }

  /**
   * Render the content of a tree as a tree using a template.
   */
  protected function renderTreeNode(&$content, $entity, EntityCollectionTreeNode $item, $langcode = NULL) {
    $element = array(
      '#children' => array(),
      '#weight' => $item->position,
      '#theme' => 'entity_collection_style_hierarchy',
    );
    if (isset($item->content)) {
      $data = EntityCollectionRow::getRow($entity)->build($entity, $item);
      $element['#parent'] = $data;
    }
    foreach ($item->getChildren() as $key => $child) {
      $this->renderTreeNode($element['#children'], $entity, $child, $langcode);
    }
    $content[] = $element;
  }
}

/**
 * Preprocess function for this plugin.
 */
function template_preprocess_entity_collection_style_hierarchy(&$variables) {
  $variables['parent'] = isset($variables['element']['#parent']) ? $variables['element']['#parent'] : 0;
  $variables['children'] = $variables['element']['#children'];
}
