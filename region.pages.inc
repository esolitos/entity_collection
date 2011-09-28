<?php

/**
 * @file
 * Page callbacks for regions.
 */

function region_page(Region $region) {
  // Return a basic region with the "full" view mode.
  return $region->buildContent('full');
}
