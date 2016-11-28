<?php

namespace Drupal\entity_collection\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Test the Entity Collection module.
 *
 * @group entity_collection
 *
 * @ingroup entity_collection
 */
class EntityCollectionTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('entity_collection');

  /**
   * The installation profile to use with this test.
   *
   * We need the 'minimal' profile in order to make sure the Tool block is
   * available.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Entity Collection tests',
      'description' => 'Test the Entity Collection module.',
      'group' => 'Entity Collection',
    );
  }

  /**
   * Test the "Access entity collections list" permission.
   *
   * Verify that:
   *
   * 1) The anonymous user cannot access the entity collections list page.
   *
   * 2) An authenticated user without the "Access entity collections list"
   * ('list entity collections') permission cannot access the entity collections
   * list page.
   *
   * 3) An authenticated user with the "Access entity collections list" ('list
   * entity collections') has access to the entity collections list page.
   */
  public function testEntityCollectionPermissionAccessEntityCollectionsList() {
    // 1) The anonymous user cannot access the entity collections list page.
    $path = '/admin/structure/entity_collection/collections';
    $this->drupalGet($path);
    $this->assertResponse(403, "Access denied to anonymous for path: $path");

    // 2) An authenticated user without the "Access entity collections list"
    // ('list entity collections') permission cannot access the entity collections
    // list page.

    // Create a user with no permissions.
    $noperms_user = $this->drupalCreateUser();
    $this->drupalLogin($noperms_user);
    // Should be the same result for forbidden paths, since the user needs
    // special permissions for these paths.
    $this->drupalGet($path);
    $this->assertResponse(403, "Access denied to generic user for path: $path");

    // 3) An authenticated user with the "Access entity collections list" ('list
    // entity collections') has access to the entity collections list page.

    // Create a user who can access the list of entity collections.
    $list_ec_user = $this->drupalCreateUser(array('list entity collections'));
    $this->drupalLogin($list_ec_user);
    // Verify that the user has access to the entity collections list.
    $this->drupalGet($path);
    $this->assertResponse(200, "Access granted to user that can list entity collections for path: $path");
    $this->drupalLogout();
  }

//  /**
//   * Various functional test of the entity collections administration.
//   *
//   * 1) Verify that anonymous users have no access to entity collection listing
//   * and creation pages.
//   *
//   * 2) Verify that "List entity collections" permission is applied.
//   *
//   * 3) Verify that "Create entity collections" permission is applied.
//   */
//  public function testEntityCollectionAdministration() {
//
//    // 1) Verify that anonymous users have no access to entity collection listing
//    // and creation pages.
//    $list_ec_path = '/admin/structure/entity_collection/collections';
//    $add_ec_path = '/admin/structure/entity_collection/add';
//    $restricted_paths = array(
//      $list_ec_path,
//      $add_ec_path,
//    );
//    // Check each of the paths to make sure we don't have access. At this point
//    // we haven't logged in any users, so the client is anonymous.
//    foreach ($restricted_paths as $path) {
//      $this->drupalGet($path);
//      $this->assertResponse(403, "Access denied to anonymous for path: $path");
//    }
//
//    // Create a user with no permissions.
//    $noperms_user = $this->drupalCreateUser();
//    $this->drupalLogin($noperms_user);
//    // Should be the same result for forbidden paths, since the user needs
//    // special permissions for these paths.
//    foreach ($restricted_paths as $path) {
//      $this->drupalGet($path);
//      $this->assertResponse(403, "Access denied to generic user for path: $path");
//    }
//
//    // 2) Verify that "List entity collections" permission is applied.
//    // Create a user who can access the list of entity collections.
//    $list_ec_user = $this->drupalCreateUser(array('list entity collections'));
//    $this->drupalLogin($list_ec_user);
//    // Verify that the user has access to the entity collections list.
//    $this->drupalGet($list_ec_path);
//    $this->assertResponse(200, "Access granted to user that can list entity collections for path: $list_ec_path");
//    // Check the menu link.
//    $this->drupalGet('');
//    $this->assertLinkByHref($list_ec_path);
//    $this->drupalLogout();
//
//    // 3) Verify that "Create entity collections" permission is applied.
//    // Create a user who can create new entity collections.
//    $create_ec_user = $this->drupalCreateUser(array('create entity collections'));
//    $this->drupalLogin($create_ec_user);
//    // Verify that the user has permission to add new entity collections.
//    $this->drupalGet($add_ec_path);
//    $this->assertResponse(200, "Access granted to user that can add entity collections for path: $add_ec_path");
//    // Check the menu links.
//    $this->drupalGet('');
//    $this->assertLinkByHref($add_ec_path);
//    $this->drupalLogout();
//  }
}
