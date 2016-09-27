<?php

namespace Drupal\entity_collection\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\entity_collection\Entity\EntityCollectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\entity_collection\EntityCollectionManager;

/**
 * Class EntityCollectionContentAdmin.
 *
 * @package Drupal\entity_collection\Controller
 */
class EntityCollectionContentAdmin extends ControllerBase {

  /**
   * Drupal\entity_collection\EntityCollectionManager definition.
   *
   * @var \Drupal\entity_collection\EntityCollectionManager
   */
  protected $entityCollectionManager;

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityCollectionManager $entity_collection_manager) {
    $this->entityCollectionManager = $entity_collection_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_collection.manager')
    );
  }

  /**
   * Admin User Interface.
   *
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $entity_collection
   * @return string Return Hello string.
   * Return Hello string.
   */
  public function adminUI(EntityCollectionInterface $entity_collection) {

//    $admin_ui_plugin = $entity_collection->getAdminUI();
    $admin_ui_plugin = $this->entityCollectionManager->getAdminUI($entity_collection);

    return $admin_ui_plugin->build();
  }

}
