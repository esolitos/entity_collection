<?php

/**
 * Simple interface for storing regions.
 */
interface RegionStorage {

  public function save($name, RegionNode $tree, $position = 0);

  public function appendItem($name, RegionNode $item);

  public function retrieve($name);

  public function delete($name, $entries);

  public function drop($name);

}

