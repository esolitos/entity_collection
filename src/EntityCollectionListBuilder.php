<?php

namespace Drupal\entity_collection;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Entity collection entities.
 */
class EntityCollectionListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Collection Name');
    $header['id'] = $this->t('Machine Name');
    $header['admin_ui'] = $this->t('Admin UI');
    $header['list_style'] = $this->t('List Style');
    $header['row_display'] = $this->t('Row Display');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['label'] = $entity->toLink();
    $row['id'] = $entity->id();
    $row['admin_ui'] = $entity->get('admin_ui');
    $row['list_style'] = $entity->get('list_style');
    $row['row_display'] = $entity->get('row_display');

    return $row + parent::buildRow($entity);
  }

}
