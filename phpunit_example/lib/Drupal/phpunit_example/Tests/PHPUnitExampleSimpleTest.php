<?php

/**
 * @file
 * SimpleTests for phpunit_example module.
 */

namespace Drupal\phpunit_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Default test case for the phpunit_example module.
 *
 * Note that this is _not_ a PHPUnit-based test. It's a functional
 * test of whether this module can be enabled properly.
 *
 * @ingroup phpunit_example
 */
class PHPUnitExampleSimpleTest extends WebTestBase {

  public static $modules = array('phpunit_example');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'PHPUnit Example Tests',
      'description' => 'Functional tests for the PHPUnit Example module',
      'group' => 'Examples',
    );
  }

  /**
   * Very simple regression test for PHPUnit Example module.
   *
   * All we do is enable PHPUnit Example and see if it can successfully
   * return its main page.
   */
  public function testController() {
    $this->drupalGet('examples/phpunit_example');
    $this->assertResponse(200, 'The PHPUnit Example description page is available.');
  }

}
