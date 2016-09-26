<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

abstract class EntityCollectionPluginBase extends PluginBase implements EntityCollectionPluginBaseInterface {


  /**
   * {@inheritdoc}
   */
  public function getConfigForm(array $form, FormStateInterface &$form_state) {
    // Empty form by default.
    return [];
  }

  public function getConfiguration() {
    // Empty form by default.
    return [];
  }

}