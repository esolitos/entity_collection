<?php

namespace Drupal\entity_collection;
use Drupal\Core\Config\ConfigFactory;

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

}
