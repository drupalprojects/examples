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
  public static $modules = array('examples', 'toolbar');

  /**
   * Test whether the module was installed.
   */
  public function testExamples() {
    // Verify that the toolbar tab and tray are showing and functioning.
    $user = $this->drupalCreateUser(array('access toolbar'));
    $this->drupalLogin($user);

    // Check for the 'Examples' tab.
    $this->drupalGet('/');
    $this->assertRaw('<nav class="toolbar-lining clearfix" role="navigation" aria-label="Developer Examples">');
    $this->assertText('Examples');
    $this->assertNoRaw('<li class="phpunit-example">');

    // Install phpunit_example and see if it appears in the toolbar. We use
    // phpunit_example because it's very light-weight.
    $this->container->get('module_installer')->install(array('phpunit_example'), TRUE);
    // SimpleTest needs for us to reset all the caches.
    $this->resetAll();

    // Verify that PHPUnit appears in the tray.
    $this->drupalGet('/');
    $this->assertRaw('<li class="phpunit-example">');
  }

}
