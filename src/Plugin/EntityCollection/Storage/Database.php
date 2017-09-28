<?php

namespace Drupal\entity_collection\Plugin\EntityCollection\Storage;

use \Drupal\Core\Database\Database as DrupalDatabase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\CollectionTree\TreeNodeInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Drupal\entity_collection\Plugin\StorageBase;

/**
 * Class Database
 *
 * @EntityCollectionStorage(
 *   id = "database",
 *   label = @Translation("Database Storage"),
 *   description = @Translation("Stores the Entity Collection content in a system database")
 * )
 */
class Database extends StorageBase {

  public function getConfigForm(array $form, FormStateInterface &$form_state) {
    $form = parent::getConfigForm($form, $form_state);

    /** @var EntityCollectionInterface $entity_collection */
    $entity_collection = $form_state->getFormObject()->getEntity();
    $settings = $entity_collection->getPluginSettings('storage');

    $form['connection'] = array(
      '#type' => 'select',
      '#title' => $this->t("Datbase"),
      '#description' => $this->t("Select which database should be used as backend."),
      '#options' => $this->getDatabaseSelectOptions(),
      '#default_value' => isset($settings['connection']) ? $settings['connection'] : NULL,
    );

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function store(EntityCollectionInterface $collection, TreeNodeInterface $tree) {
    // TODO: Implement store() method.
  }

  /**
   * {@inheritdoc}
   */
  public function load(EntityCollectionInterface $collection) {
    // TODO: Implement load() method.
  }

  /**
   * {@inheritdoc}
   */
  public function truncate(EntityCollectionInterface $collection) {
    // TODO: Implement truncate() method.
  }

  /**
   * Generates an array of available database backend
   *
   * @return array
   */
  private function getDatabaseSelectOptions() {
    $connections = DrupalDatabase::getAllConnectionInfo();
    $options = [];
    foreach ($connections as $connection_id => $definition) {
      $options[$connection_id] = $connection_id;
    }

    return $options;
  }

  /**
   * Sets the configuration for this plugin instance.
   *
   * @param array $configuration
   *   An associative array containing the plugin's configuration.
   */
  public function setConfiguration(array $configuration) {
    // TODO: Implement setConfiguration() method.
  }
}
