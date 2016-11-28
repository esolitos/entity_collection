<?php

namespace Drupal\entity_collection;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Defines an access controller for the Entity Collection entity.
 *
 * @see \Drupal\entity_collection\Entity
 *
 * @ingroup entity_collection
 */
class EntityCollectionAccessController extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  public function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Example code:
    if ($operation == 'view') {
      return TRUE;
    }
    if ($operation == 'delete' && $entity->isNew()) {
      return AccessResult::forbidden()->addCacheableDependency($entity);
    }
    if ($admin_permission = $this->entityType->getAdminPermission()) {
      return AccessResult::allowedIfHasPermission($account, $this->entityType->getAdminPermission());
    }
    else {
      // No opinion.
      return AccessResult::neutral();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    if ($admin_permission = $this->entityType->getAdminPermission()) {
      return AccessResult::allowedIfHasPermission($account, $admin_permission);
    }
    else {
      // No opinion.
      return AccessResult::neutral();
    }
  }

}
