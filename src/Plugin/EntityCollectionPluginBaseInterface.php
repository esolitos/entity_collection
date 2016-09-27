<?php


namespace Drupal\entity_collection\Plugin;

use Drupal\Component\Plugin\ConfigurablePluginInterface;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;

interface EntityCollectionPluginBaseInterface extends PluginInspectionInterface, ConfigurablePluginInterface {

  /**
   * Generates the configuration form for each plugin.
   * 
   * @param array $form
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   * @return array A form that will be included in the configuration page
   */
  public function getConfigForm(array $form, FormStateInterface &$form_state);

}