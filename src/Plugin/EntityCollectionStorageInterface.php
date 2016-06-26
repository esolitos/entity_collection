<?php

namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\Entity\EntityCollection;
use Drupal\entity_collection\TreeNodeInterface;

/**
 * Defines an interface for EntityCollection Storage plugins.
 */
interface EntityCollectionStorageInterface extends EntityCollectionPluginBaseInterface {


  /**
   * Generates the configuration form for the given Storage
   *
   * @param $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array
   */
  public function configForm($form, FormStateInterface &$form_state);

  /**
   * Stores an entity collection in the backend.
   *
   * @param EntityCollection $collection
   *   The collection to which the content belongs.
   * @param TreeNodeInterface $tree
   *   The Tree to be saved
   * @param array $contexts
   *   An array of contexts
   */
  public function store(EntityCollection $collection, TreeNodeInterface $tree, $contexts = array());

  /**
   * Retrieve the content of a collection.
   *
   * @param EntityCollection $collection
   *   The collection to which the content belongs.
   * @param array $contexts
   *   An array of contexts to use.
   */
  public function load(EntityCollection $collection, $contexts = array());


  /**
   * Remove all content in the entity collection.
   *
   * @param EntityCollection $collection
   */
  public function truncate(EntityCollection $collection);
}
