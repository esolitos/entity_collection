<?php

/**
 * Simple interface for storing regions.
 */
interface RegionStorage {

  public function save($name, $regions);

  public function retrieve($name);

  public function delete($name);

}

