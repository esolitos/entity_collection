<?php


namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

interface EntityCollectionPluginBaseInterface extends PluginInspectionInterface {

  /**
   * Generates the configuration form for each plugin.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array A form that will be included in the configuration page
   */
  public function getConfigForm(array $form, FormStateInterface &$form_state);

  public function getConfiguration();
}