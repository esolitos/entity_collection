<?php

namespace Drupal\entity_collection;
use Drupal\Core\Config\ConfigFactory;
use Drupal\entity_collection\Entity\EntityCollection;

/**
 * Class EntityCollectionManager.
 *
 * @package Drupal\entity_collection
 */
class EntityCollectionManager implements EntityCollectionManagerInterface {

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;
  /**
   * Constructor.
   */
  public function __construct(ConfigFactory $config_factory) {
    $this->configFactory = $config_factory;
  }

  public function getCollection($name) {
    // TODO: Implement getCollection() method.
  }

  public function getListStyle(EntityCollection $collection) {
    // TODO: Implement getListStyle() method.
  }

  public function getRowDisplay(EntityCollection $collection) {
    // TODO: Implement getRowDisplay() method.
  }


  public function getStorage(EntityCollection $collection) {
    // TODO: Implement getStorage() method.
  }


}
