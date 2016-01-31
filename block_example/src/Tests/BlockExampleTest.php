<?php

/**
 * @file
 * Definition of Drupal\block_example\Tests\BlockExampleTest.
 */

namespace Drupal\block_example\Tests;

use Drupal\Component\Utility\Unicode;
use Drupal\simpletest\WebTestBase;

/**
 * Test the configuration options and block created by Block Example module.
 *
 * @ingroup block_example
 *
 * @group block_example
 * @group examples
 */
class BlockExampleTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('block', 'block_example');

  /**
   * Tests block_example functionality.
   */
  public function testBlockExampleBasic() {
    // Create user.
    $web_user = $this->drupalCreateUser(array('administer blocks'));
    // Login the admin user.
    $this->drupalLogin($web_user);

    $theme_name = \Drupal::config('system.theme')->get('default');

    // Verify the blocks are listed to be added.
    $this->drupalGet('/admin/structure/block/library/' . $theme_name, ['query' => ['region' => 'content']]);
    $this->assertRaw(t('Title of first block (example_configurable_text)'), 'Block configurable-string found.');
    $this->assertRaw(t('Example: empty block'), 'Block empty-block found.');
    $this->assertRaw(t('Example: uppercase this please'), 'Block uppercase found.');

    // Define and place blocks.
    $settings_configurable = array(
      'label' => t('Title of first block (example_configurable_text)'),
      'id' => 'block_example_example_configurable_text',
      'theme' => $theme_name,
    );
    $this->drupalPlaceBlock('example_configurable_text', $settings_configurable);

    $settings_uppercase = array(
      'label' => t('Configurable block to be uppercased'),
      'id' => 'block_example_example_uppercased',
      'theme' => $theme_name,
    );
    $this->drupalPlaceBlock('example_uppercase', $settings_uppercase);

    $settings_empty = array(
      'label' => t('Example: empty block'),
      'id' => 'block_example_example_empty',
      'theme' => $theme_name,
    );
    $this->drupalPlaceBlock('example_empty', $settings_empty);

    // Verify that blocks are there. Empty block will not be shown, because it
    // holds an empty array.
    $this->drupalGet('');
    $this->assertRaw($settings_configurable['label'], 'Block configurable test not found.');
    $this->assertNoRaw($settings_uppercase['label'], 'Block uppercase with normal label not found.');
    $this->assertRaw(Unicode::strtoupper($settings_uppercase['label']), 'Block uppercase with uppercased label found.');
    $this->assertNoRaw($settings_empty['label'], 'Block empty not found.');

    // Change content of configurable text block.
    $edit = array(
      'settings[block_example_string_text]' => $this->randomMachineName(),
    );
    $this->drupalPostForm('/admin/structure/block/manage/' . $settings_configurable['id'], $edit, t('Save block'));

    // Verify that new content is shown.
    $this->drupalGet('');
    $this->assertRaw($edit['settings[block_example_string_text]'], 'Content of configurable text block successfully verified.');
  }

}
