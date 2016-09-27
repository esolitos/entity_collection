<?php

namespace Drupal\entity_collection\Plugin;


/**
 * Defines an interface for Entity Collection: Admin UI plugins.
 */
interface AdminUIInterface extends EntityCollectionPluginBaseInterface {


  public function build();

}
