<?php

/**
 * @file
 * SimpleTests for js_example module.
 */

namespace Drupal\js_example\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Minimal test case for the js_example module.
 *
 * @ingroup js_example
 */
class JsExampleTest extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('js_example');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'JavaScript Examples Tests',
      'description' => 'Functional tests for the js_example module',
      'group' => 'Examples',
    );
  }

  /**
   * Test all the paths defined by our module.
   */
  public function testJsExample() {
    $paths = array(
      'examples/js_example',
      'examples/js_example/weights',
      'examples/js_example/accordion',
    );
    foreach ($paths as $path) {
      $this->drupalGet($path);
      $this->assertResponse(200, 'Visited ' . $path);
    }
  }

}
