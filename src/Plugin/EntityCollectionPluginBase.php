<?php
/**
 * Created by PhpStorm.
 * User: esolitos
 * Date: 26/06/16
 * Time: 12:54
 */

namespace Drupal\entity_collection\Plugin;


use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\Entity\EntityCollectionInterface;

abstract class EntityCollectionPluginBase implements EntityCollectionPluginBaseInterface {


  /**
   * {@inheritdoc}
   */
  public function getConfigForm(EntityCollectionInterface $collection, array $form, FormStateInterface &$form_state) {
    // Empty form by default.
    return [];
  }

}