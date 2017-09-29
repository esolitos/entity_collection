<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

abstract class PluginManagerBase extends DefaultPluginManager implements PluginManager {

  public function createInstance($plugin_id, array $configuration = [], EntityCollectionInterface $entity_collection = NULL) {
    if (empty($entity_collection) && !is_a($configuration['entity_collection'], EntityCollectionInterface::class)) {
      throw new \InvalidArgumentException('Entity Collection not provided.');
    }
    if (empty($configuration['entity_collection'])) {
      $configuration['entity_collection'] = $entity_collection;
    }

    return parent::createInstance($plugin_id, $configuration);
  }

}
