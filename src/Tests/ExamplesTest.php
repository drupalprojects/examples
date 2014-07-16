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
 * @group examples
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
   * Test whether the module was installed.
   */
  public function testExamples() {
    $this->assertTrue(\Drupal::moduleHandler()->moduleExists('examples'));
  }

}
