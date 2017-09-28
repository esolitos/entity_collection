<?php

namespace Drupal\entity_collection\Plugin;


use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

interface PluginManager extends PluginManagerInterface {

  public function createInstance($plugin_id, array $configuration = [], EntityCollectionInterface $entity_collection = NULL);
}
