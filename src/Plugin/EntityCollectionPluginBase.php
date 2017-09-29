<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginBase;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

abstract class EntityCollectionPluginBase extends PluginBase implements EntityCollectionPluginBaseInterface {

  /**
   * @var \Drupal\entity_collection\Entity\EntityCollectionInterface
   */
  protected $entityCollection;

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    if (isset($configuration['entity_collection']) && is_a($configuration['entity_collection'], EntityCollectionInterface::class)) {
      $this->entityCollection = $configuration['entity_collection'];
    }
    else if (empty($configuration['entity_collection'])) {
      throw new \ArgumentCountError("Missing required entity_collection in the given configuration.");
    }
    else {
      throw new \InvalidArgumentException("The parameter entity_collection in the given configuration is not a valid EntityCollection.");
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigForm(array $form, FormStateInterface &$form_state) {
    // Empty form by default.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    // Empty config by default.
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    // Empty config by default.
    return [];
  }

  public function calculateDependencies() {
    // No dependencies by default.
    return [];
  }



}
