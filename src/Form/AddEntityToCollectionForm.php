<?php

namespace Drupal\entity_collection\Form;

use Drupal\Component\Utility\Crypt;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeRepositoryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Site\Settings;
use Drupal\entity_collection\EntityCollectionManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\entity_collection\EntityCollectionManager;
use Drupal\Core\Entity\EntityTypeBundleInfo;
use Drupal\Core\Entity\EntityTypeRepository;

/**
 * Class AddEntityToCollectionForm.
 */
class AddEntityToCollectionForm extends FormBase {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;
  /**
   * Drupal\entity_collection\EntityCollectionManager definition.
   *
   * @var \Drupal\entity_collection\EntityCollectionManager
   */
  protected $entityCollectionManager;
  /**
   * Drupal\Core\Entity\EntityTypeBundleInfo definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfo
   */
  protected $entityTypeBundleInfo;
  /**
   * Drupal\Core\Entity\EntityTypeRepository definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeRepository
   */
  protected $entityTypeRepository;
  /**
   * Constructs a new AddEntityToCollectionForm object.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EntityTypeRepositoryInterface $entity_type_repository,
    EntityTypeBundleInfoInterface $entity_type_bundle_info,
    EntityCollectionManagerInterface $entity_collection_manager
  ) {
    $this->entityTypeManager = $entity_type_manager;
    $this->entityCollectionManager = $entity_collection_manager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    $this->entityTypeRepository = $entity_type_repository;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('entity_type.repository'),
      $container->get('entity_type.bundle.info'),
      $container->get('entity_collection.manager')
    );
  }


  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_entity_to_collection_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['entity_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Entity type'),
      '#options' => $this->entityTypeRepository->getEntityTypeLabels(TRUE),
//      '#ajax' => [
//        'callback' => '::getBundleSelectItem',
//        'wrapper' => 'entity-bundle-wrap',
//      ],
      '#ajax' => [
        'callback' => '::getEntitySelectItem',
        'wrapper' => 'entity-select-wrap',
      ],
    ];

//    $form['entity_bundle'] = $this->getBundleSelectItem($form, $form_state);
    $form['entity_selected'] = $this->getEntitySelectItem($form, $form_state);


    $form['actions'] = [
      '#type' => 'form_actions',
    ];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Select entity'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Display result.
    foreach ($form_state->getValues() as $key => $value) {
      drupal_set_message($key . ': ' . $value);
    }

  }

  public function getBundleSelectItem($form, FormStateInterface $form_state) {
    $form_item = [
      '#type' => 'select',
      '#title' => $this->t('Bundle'),
      '#prefix' => '<div id="entity-bundle-wrap">',
      '#suffix' => '</div>',
    ];

    $entity_type = $form_state->getValue('entity_type');
    $options = $this->entityTypeBundleInfo->getBundleInfo($entity_type);

    // Ignore the "default" bundle, which means basically that the entity has no bundles.
    if (!empty(count($options)) && !(count($options) === 1 && isset($options[$entity_type]))) {
      array_walk($options, function (&$item, $key) {
        $item = $item['label'];
      });

      $form_item['#options'] = $options;
    }
    else {
      $form_item['#type'] = 'value';
      $form_item['#value'] = FALSE;
    }


    return $form_item;
  }

  public function getEntitySelectItem($form, FormStateInterface $form_state) {
    $item = [
      '#type' => 'container',
      '#attributes' => [
        'id' => 'entity-select-wrap',
      ],
      '#states' => [
        'visible' => [
          ':input[name="label_search"]' => ['empty' => FALSE],

        ],
      ],
    ];

    $key_value_storage = \Drupal::keyValue('entity_autocomplete');
    $entity_type = $form_state->getValue('entity_type') ?: 'node';

    if (isset($entity_type)) {
      $form_state->setRebuild();

      $selection_handler = 'default';
      $selection_settings = [
        'match_operator' => 'CONTAINS',
      ];
      $data = serialize($selection_settings) . $entity_type . $selection_handler;
      $selection_settings_key = Crypt::hmacBase64($data, Settings::getHashSalt());
      if (!$key_value_storage->has($selection_settings_key)) {
        $key_value_storage->set($selection_settings_key, $selection_settings);
      }

      $item['entity_id'] = [
        '#type' => 'textfield',
        '#title' => "Entity",
        '#autocomplete_route_name' => 'system.entity_autocomplete',
        '#autocomplete_route_parameters' => [
          'target_type' => $entity_type,
          'selection_handler' => $selection_handler,
          'selection_settings_key' => $selection_settings_key,
        ],
      ];
    }

    return $item;
  }
}
