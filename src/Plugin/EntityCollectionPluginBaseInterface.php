<?php
/**
 * Created by PhpStorm.
 * User: esolitos
 * Date: 26/06/16
 * Time: 12:54
 */

namespace Drupal\entity_collection\Plugin;


use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

interface EntityCollectionPluginBaseInterface extends PluginInspectionInterface {

  /**
   * Generates the cinfiguration form for each plugin.
   * 
   * @param \Drupal\entity_collection\Entity\EntityCollectionInterface $collection
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array A form that will be included in the configuration page
   */
  public function getConfigForm(EntityCollectionInterface $collection, array $form, FormStateInterface &$form_state);
}