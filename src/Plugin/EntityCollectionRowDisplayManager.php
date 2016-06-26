<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Entity Collection: Row Display plugin manager.
 */
class EntityCollectionRowDisplayManager extends DefaultPluginManager {


  /**
   * Constructor for EntityCollectionRowDisplayManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/EntityCollectionRowDisplay', $namespaces, $module_handler, 'Drupal\entity_collection\Plugin\EntityCollectionRowDisplayInterface', 'Drupal\entity_collection\Annotation\EntityCollectionRowDisplay');

    $this->alterInfo('entity_collection_entity_collection_row_display_info');
    $this->setCacheBackend($cache_backend, 'entity_collection_entity_collection_row_display_plugins');
  }

}
