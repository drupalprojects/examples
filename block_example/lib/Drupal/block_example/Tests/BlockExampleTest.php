<?php

/**
 * @file
 * Definition of Drupal\block_example\Tests\BlockExampleTest.
 */

namespace Drupal\block_example\Tests;

use Drupal\simpletest\WebTestBase;

class BlockExampleTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('block', 'search', 'block_example');

  protected $web_user;

  public static function getInfo() {
    return array(
      'name' => 'Block example functionality',
      'description' => 'Test the configuration options and block created by Block Example module.',
      'group' => 'Examples',
    );
  }

  /**
   * Enable modules and create user with specific permissions.
   */
  function setUp() {
    parent::setUp();
    // Create user. Search content permission granted for the search block to
    // be shown.
    $this->web_user = $this->drupalCreateUser(array('administer blocks', 'search content'));
  }

  /**
   * Login user, create an example node, and test block functionality through
   * the admin and user interfaces.
   */
  function testBlockExampleBasic() {
    // Login the admin user.
    $this->drupalLogin($this->web_user);
    $theme_name = config('system.theme')->get('default');

    // Find the blocks in the settings page.
    $this->drupalGet('admin/structure/block/list/block_plugin_ui:' . $theme_name . '/add');
    $this->assertRaw(t('Title of first block (example_configurable_text)'), 'Block configurable-string found.');
    $this->assertRaw(t('Example: empty block'), 'Block empty-block found.');

    // Add blocks
    // Create a new block and make sure it gets uppercased.
    $edit = array(
      'settings[label]' => t('Title of first block (example_configurable_text)'),
      'machine_name' => 'block_example_example_configurable_text',
      'region' => 'sidebar_first',
    );
    $this->drupalPost('admin/structure/block/add/example_configurable_text/' . $theme_name , $edit, t('Save block'));
    $this->assertText(t('The block configuration has been saved.'));
    $this->assertText($edit['settings[label]']);

    $this->drupalGet('admin/structure/block');
    $this->assertLinkByHref(url('admin/structure/block/manage/' . $theme_name . '.block_example_example_configurable_text/configure'));

    $edit = array(
      'settings[label]' => t('Configurable block to be uppercased'),
      'machine_name' => 'uppercased_block',
      'region' => 'sidebar_first',
    );
    $this->drupalPost('admin/structure/block/add/example_uppercase/' . $theme_name, $edit, t('Save block'));

    $edit = array(
      'settings[label]' => t('Example: empty block'),
      'machine_name' => 'block_example_example_empty',
      'region' => 'sidebar_first',
    );
    $this->drupalPost('admin/structure/block/add/example_empty/' . $theme_name, $edit, t('Save block'));

    // Verify that blocks are not shown
    $this->drupalGet('/');
    $this->assertRaw(t('Title of first block (example_configurable_text)'), 'Block configurable test not found.');
    $this->assertNoRaw(t('Title of second block (example_empty)'), 'Block empty not found.');

    // Verify that blocks are there. Empty block will not be shown, because it is empty
    $this->drupalGet('/');
    $this->assertRaw(t('Title of first block (example_configurable_text)'), 'Block configurable text found.');

    // Change content of configurable text block
    $edit = array(
      'settings[block_example_string_text]' => $this->randomName(),
    );
    $this->drupalPost('admin/structure/block/manage/' . $theme_name . '.block_example_example_configurable_text/configure', $edit, t('Save block'));

    // Verify that new content is shown
    $this->drupalGet('/');
    $this->assertRaw($edit['settings[block_example_string_text]'], 'Content of configurable text block successfully verified.');
  }

}
