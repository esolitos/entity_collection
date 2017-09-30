<?php

namespace Drupal\entity_collection\Plugin\EntityCollection\Storage;

use \Drupal\Core\Database\Database as DrupalDatabase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\CollectionTree\Exception\InvalidTreeNodePropertyException;
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

  const STORAGE_COLLECTION_TABLE = 'entity_collection_storage';

  const STORAGE_PROPERTIES_TABLE = 'entity_collection_storage_properties';

  const STORAGE_ID_PROPERTY_NAME = '_storage_id';

  /** @var \Drupal\Core\Database\Connection  */
  protected $dbConnection;

  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $backend = isset($this->configuration['connection']) ? $this->configuration['connection'] : 'default';
    $this->dbConnection = DrupalDatabase::getConnection($backend);
  }


  public function getConfigForm(array $form, FormStateInterface &$form_state) {
    $form = parent::getConfigForm($form, $form_state);;

    $settings = $this->entityCollection->getPluginSettings('storage');

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
  public function store(TreeNodeInterface $treeNode) {
    if (!$treeNode->isRoot() && $treeNode->entity()) {
      $fields = $this->getRowValues($treeNode);

      if ($this->getTreeNodeId($treeNode)) {
        $query = $this->dbConnection->merge(self::STORAGE_COLLECTION_TABLE);
        $query->key('id', $treeNode->getProperty(self::STORAGE_ID_PROPERTY_NAME));
      }
      else {
        $query = $this->dbConnection->insert(self::STORAGE_COLLECTION_TABLE);
      }

      $query->fields($fields)
        ->execute();
    }
    // Store the children
    foreach ($treeNode->getIterator() as $item) {
      $this->store($item);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function load() {
    // TODO: Implement load() method.
  }

  /**
   * {@inheritdoc}
   */
  public function truncate() {
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

  private function getRowValues(TreeNodeInterface $treeNode): array {
    return [
      'collection' => $this->entityCollection->id(),
      'entity_type' => $treeNode->entity()->getEntityTypeId(),
      'entity_id' => $treeNode->entity()->id(),
      // TODO
      'depth' => 0,
      'parent' => 0,
      'weight' => 0,
    ];
  }

  /**
   * @param $treeNode
   *
   * @return bool
   */
  private function getTreeNodeId($treeNode) {
    try {
      return $treeNode->getProperty(self::STORAGE_ID_PROPERTY_NAME);
    }
    catch(InvalidTreeNodePropertyException $exception) {
      return FALSE;
    }
  }
}
