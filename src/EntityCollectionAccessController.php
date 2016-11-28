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

    switch ($operation) {

      case 'view':
      case 'view label':
        // At the moment, viewing the entity collection is open to everyone.
        // @TODO upgrade this logic
        return TRUE;
        break;

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit entity collections');
        break;

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete entity collections');
        break;
      default:
        // @TODO Not sure if this is needed
        return AccessResult::neutral();
        break;
    }

    // @TODO what is this? Is this needed?
    // It's not called right now, as the above switch statement covers all cases.
    if ($operation == 'delete' && $entity->isNew()) {
      return AccessResult::forbidden()->addCacheableDependency($entity);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'create entity collections');
  }

}
