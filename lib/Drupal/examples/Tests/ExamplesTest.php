<?php

/**
 * @file
 * SimpleTests for examples module.
 */

namespace Drupal\examples\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Minimal test case for the examples module.
 *
 * @ingroup examples
 */
class ExamplesTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('examples');

  /**
   * Implements getInfo().
   *
   * @return array
   *   Testing info for SimpleTest.
   */
  public static function getInfo() {
    return array(
      'name' => 'Examples test',
      'description' => 'Functional tests for the Examples module',
      'group' => 'Examples',
    );
  }

  /**
   * Test whether the module was installed.
   *
   * @todo: Replace module_exists with Drupal::moduleHandler() ..etc.
   */
  public function testExamples() {
    $this->assertTrue(module_exists('examples'));
  }

}
