<?php

/**
 * @file
 * Definition of Drupal\block_example\Tests\BlockExampleMenuTest.
 */

namespace Drupal\block_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Test the user-facing menus in Block Example.
 *
 * @ingroup block_example
 */
class BlockExampleMenuTest extends WebTestBase {

  /**
   * Modules to enable.
   *
   * @var array
   */
  public static $modules = array('block', 'block_example');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Block Example Menu Test',
      'description' => 'Test the user-facing menus in Block Example.',
      'group' => 'Examples',
    );
  }

  /**
   * Tests block_example menus.
   */
  public function testBlockExampleMenu() {
    $this->drupalGet('examples/block_example');
    $this->assertResponse(200, 'Description page exists.');
  }

}
