<?php
/**
 * @file
 * Simple class for viewing a flat list of entities.
 */

/**
 * Simple class for viewing a flat list of entities.
 */
class EntityCollectionStyleFlat extends EntityCollectionStyle {

  /**
   * Build the content in the entity collection.
   * @see EntityCollectionStyle::build().
   */
  public function build(EntityCollection $entity, EntityCollectionTreeNode $tree, $langcode = NULL, $settings = array()) {
    $content = array();
    // Get a flat list of everything. We don't care about
    // grouping, we just want a flat array, sorted properly.

    // Clear zero or empty values.
    $settings = array_filter($settings);

    // Extend defaults with custom settings.
    $settings = array_merge(array(
      'offset' => 0,
      'length' => $tree->totalCount(),
    ), $settings);

    $list = array_splice($tree->getFlat(), $settings['offset'], $settings['length']);

    $i = 0;
    foreach ($list as $key => $item) {
      $data = EntityCollectionRow::getRow($entity)->build($entity, $item);
      $content[$key] = array(
        'item' => $data,
        '#weight' => $i,
      );
      $i++;
    }
    return $content;
  }
}

