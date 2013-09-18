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
   * Tests block_example functionality.
   */
  function testBlockExampleBasic() {
    // Login the admin user.
    $this->drupalLogin($this->web_user);
    $theme_name = config('system.theme')->get('default');

    // Verify the blocks are listed to be added.
    $this->drupalGet('admin/structure/block/list/' . $theme_name);
    $this->assertRaw(t('Title of first block (example_configurable_text)'), 'Block configurable-string found.');
    $this->assertRaw(t('Example: empty block'), 'Block empty-block found.');
    $this->assertRaw(t('Example: uppercase this please'), 'Block uppercase found.');

    // Define and place blocks.
    $example_configurable_text = array(
      'label' => t('Title of first block (example_configurable_text)'),
      'machine_name' => 'block_example_example_configurable_text',
    );
    $this->drupalPlaceBlock('example_configurable_text', $example_configurable_text, $theme_name);
    $example_uppercase = array(
      'label' => t('Configurable block to be uppercased'),
      'machine_name' => 'uppercased_block',
    );
    $this->drupalPlaceBlock('example_uppercase', $example_uppercase, $theme_name);
    $example_empty = array(
      'label' => t('Example: empty block'),
      'machine_name' => 'block_example_example_empty',
    );
    $this->drupalPlaceBlock('example_empty', $example_empty, $theme_name);

    // Verify that blocks are there. Empty block will not be shown, because it is empty.
    $this->drupalGet('/');
    $this->assertRaw($example_configurable_text['label'], 'Block configurable test not found.');
    $this->assertNoRaw($example_uppercase['label'], 'Block uppercase with normal label not found.');
    $this->assertRaw(drupal_strtoupper($example_uppercase['label']), 'Block uppercase with uppercased label found.');
    $this->assertNoRaw($example_empty['label'], 'Block empty not found.');

    // Change content of configurable text block.
    $edit = array(
      'settings[block_example_string_text]' => $this->randomName(),
    );
    $this->drupalPostForm('admin/structure/block/manage/' . $theme_name . '.block_example_example_configurable_text', $edit, t('Save block'));

    // Verify that new content is shown.
    $this->drupalGet('/');
    $this->assertRaw($edit['settings[block_example_string_text]'], 'Content of configurable text block successfully verified.');
  }

}
