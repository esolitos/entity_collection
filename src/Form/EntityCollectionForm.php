<?php

namespace Drupal\entity_collection\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_collection\Plugin\ListStyleManager;
use Drupal\entity_collection\Plugin\EntityCollectionRowDisplayManager;
use Drupal\entity_collection\Plugin\StorageManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EntityCollectionForm.
 *
 * @package Drupal\entity_collection\Form
 */
class EntityCollectionForm extends EntityForm {

  /**
   * The entity being used by this form.
   *
   * @var \Drupal\entity_collection\Entity\EntityCollectionInterface
   */
  protected $entity;

  /**
   * @var \Drupal\entity_collection\Plugin\ListStyleManager
   */
  protected $listStyleManager;

  /**
   * @var \Drupal\entity_collection\Plugin\StorageManager
   */
  protected $storageManager;

  /**
   * @var \Drupal\entity_collection\Plugin\EntityCollectionRowDisplayManager
   */
  protected $rowDisplayManager;

  /**
   * EntityCollectionForm constructor.
   * @param \Drupal\entity_collection\Plugin\StorageManager $storage_manager
   * @param \Drupal\entity_collection\Plugin\ListStyleManager $list_style_manager
   * @param \Drupal\entity_collection\Plugin\EntityCollectionRowDisplayManager $row_display_manager
   */
  function __construct(StorageManager $storage_manager, ListStyleManager $list_style_manager, EntityCollectionRowDisplayManager $row_display_manager) {
    $this->storageManager = $storage_manager;
    $this->listStyleManager = $list_style_manager;
    $this->rowDisplayManager = $row_display_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.entity_collection_storage'),
      $container->get('plugin.manager.entity_collection_list_style'),
      $container->get('plugin.manager.entity_collection_row_display')
    );
  }

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
      '#description' => $this->t("The human-readable Entity Collection's name."),
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

    $storage_options = array();
    foreach ($this->storageManager->getDefinitions() as $id => $definition) {
      $storage_options[$id] = $definition['label'];
    }
    $form['storage'] = array(
      '#type' => 'select',
      '#title' => $this->t("Storage"),
      '#options' => $storage_options,
      '#default_value' => $entity_collection->get('storage'),
      '#empty_option' => $this->t("Select the storage backend."),
      '#empty_value' => '',
    );

    if ( $entity_collection->isStorageConfigured() ) {
      $form['storage_config'] = $entity_collection->getStorage()->getConfigForm($form, $form_state);
    }
    else {
      $form['storage_config'] = array(
        '#type' => 'details',
        '#title' => $this->t("Storage Settings"),
        '#prefix' => '<div class="storage-settings-wrapper">',
        '#suffix' => '</div>',
        '#states' => array(
          'invisible' => array(
            ':input[name="storage"]' => array('value' => ''),
          ),
        ),
      );
    }

    $list_style_options = array();
    foreach ($this->listStyleManager->getDefinitions() as $id => $definition) {
      $list_style_options[$id] = $definition['label'];
    }
    $form['list_style'] = array(
      '#type' => 'select',
      '#title' => $this->t("List Style"),
      '#options' => $list_style_options,
      '#default_value' => $entity_collection->get('list_style'),
      '#empty_option' => $this->t("Select the List Style."),
      '#empty_value' => '',
    );

    $row_display_options = array();
    foreach ($this->rowDisplayManager->getDefinitions() as $id => $definition) {
      $row_display_options[$id] = $definition['label'];
    }
    $form['row_display'] = array(
      '#type' => 'select',
      '#title' => $this->t("Row Display"),
      '#options' => $row_display_options,
      '#default_value' => $entity_collection->get('row_display'),
      '#empty_option' => $this->t("Select a Row Display."),
      '#empty_value' => '',
    );

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
    $form_state->setRedirectUrl($entity_collection->toUrl('edit-form'));
  }

}
