<?php

namespace Drupal\entity_collection\Plugin\EntityCollection\AdminUI;

use Drupal\entity_collection\Plugin\AdminUIBase;

/**
 * Class FlatList
 *
 * @EntityCollectionAdminUI(
 *   id = "table_form",
 *   label = @Translation("Simple Table Form"),
 *   description = @Translation("Defines the default Admin UI for the module, just a simple draggable table.")
 * )
 */
class TableForm extends AdminUIBase {

  public function build() {
    dsm($this);
    return [
      '#type' => 'markup',
      '#markup' => $this->t('TableForm built.'),
    ];
  }


}