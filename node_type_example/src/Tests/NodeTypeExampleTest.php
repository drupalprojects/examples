<?php

/**
 * @file
 * Definition of Drupal\node_type_example\Tests\NodeTypeExampleTest.
 */

namespace Drupal\node_type_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Tests for the node_type_example module.
 *
 * @ingroup node_type_example
 */
class NodeTypeExampleTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('node', 'node_type_example');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Node Type Example Test',
      'description' => 'Test that our content types are successfully created.',
      'group' => 'Examples',
    );
  }

  /**
   * Test our new content types.
   *
   * Tests for the following:
   *
   * - That our content types appear in the user interface.
   * - That our unlocked content type is unlocked.
   * - That our locked content type is locked.
   * - That we can create content using the user interface.
   * - That our created content does appear in the database.
   */
  public function testNodeTypes() {
    // Log in an admin user.
    $admin_user = $this->drupalCreateUser(array('administer content types'));
    $this->drupalLogin($admin_user);

    // Get a list of content types.
    $this->drupalGet('admin/structure/types');
    // Verify that these content types show up in the user interface.
    $this->assertRaw('Example: Basic Content Type', 'Basic content type found.');
    $this->assertRaw('Example: Locked Content Type', 'Locked content type found.');

    // Check for the locked status of our content types.
    // $nodeType will be of type Drupal\node\NodeTypeInterface
    $node_type = node_type_load('basic_content_type');
    $this->assertTrue($node_type, 'basic_content_type exists.');
    if ($node_type) {
      $this->assertFalse($node_type->isLocked(), 'basic_content_type is not locked.');
    }
    $node_type = node_type_load('locked_content_type');
    $this->assertTrue($node_type, 'locked_content_type exists.');
    if ($node_type) {
      $this->assertEqual('locked_content_type', $node_type->isLocked(), 'locked_content_type is locked.');
    }

    // Log in a content creator.
    $creator_user = $this->drupalCreateUser(array('create basic_content_type content'));
    $this->drupalLogin($creator_user);

    // Create a node.
    $edit = array();
    $edit['title[0][value]'] = $this->randomName(8);
    $edit['body[0][value]'] = $this->randomName(16);
    $this->drupalPostForm('node/add/basic_content_type', $edit, t('Save'));

    // Check that the Basic page has been created.
    $this->assertRaw(t('!post %title has been created.', array('!post' => 'Example: Basic Content Type', '%title' => $edit['title[0][value]'])), 'Basic page created.');

    // Check that the node exists in the database.
    $node = $this->drupalGetNodeByTitle($edit['title[0][value]']);
    $this->assertTrue($node, 'Node found in database.');
  }

}
