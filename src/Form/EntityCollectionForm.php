<?php

namespace Drupal\entity_collection\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class EntityCollectionForm.
 *
 * @package Drupal\entity_collection\Form
 */
class EntityCollectionForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $entity_collection = $this->entity;
    $form['label'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $entity_collection->label(),
      '#description' => $this->t("Label for the Entity collection."),
      '#required' => TRUE,
    );

    $form['id'] = array(
      '#type' => 'machine_name',
      '#default_value' => $entity_collection->id(),
      '#machine_name' => array(
        'exists' => '\Drupal\entity_collection\Entity\EntityCollection::load',
      ),
      '#disabled' => !$entity_collection->isNew(),
    );

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity_collection = $this->entity;
    $status = $entity_collection->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Entity collection.', [
          '%label' => $entity_collection->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Entity collection.', [
          '%label' => $entity_collection->label(),
        ]));
    }
    $form_state->setRedirectUrl($entity_collection->urlInfo('collection'));
  }

}
