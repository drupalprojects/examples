<?php

/**
 * @file
 * SimpleTests for the PHPUnit example module.
 */

namespace Drupal\phpunit_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Minimal test case for the PHPUnit example module.
 *
 * @ingroup phpunit_example
 */
class PHPUnitExampleTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('phpunit_example');

  /**
   * Implements getInfo().
   *
   * @return array
   *   Testing info for SimpleTest.
   */
  public static function getInfo() {
    return array(
      'name' => 'PHPUnit Example test',
      'description' => 'Functional tests for the PHPUnit Example module',
      'group' => 'Examples',
    );
  }

  /**
   * Test whether the module was installed.
   *
   * @todo: Replace module_exists with Drupal::moduleHandler() ..etc.
   */
  public function testExamples() {
    $this->assertTrue(module_exists('phpunit_example'));
  }

}
