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
    $this->drupalGet('');

    // Assert that the tab registered by example is present.
    $this->assertLink('Examples');
    $this->assertRaw('id="toolbar-item-examples"');

    // Assert that the tray registered by example is present.
    $this->assertRaw('id="toolbar-item-examples-tray"');
    $this->assertRaw('<nav class="toolbar-lining clearfix" role="navigation" aria-label="Developer Examples">');

    // Assert that PHPUnit link does not appears in the tray.
    $this->assertNoLink('PHPUnit example');
    $this->assertNoRaw('<li class="phpunit-example">');

    // Install phpunit_example and see if it appears in the toolbar. We use
    // phpunit_example because it's very light-weight.
    $this->container->get('module_installer')->install(array('phpunit_example'), TRUE);
    // SimpleTest needs for us to reset all the caches.
    $this->resetAll();

    // Verify that PHPUnit appears in the tray.
    $this->drupalGet('');
    $this->assertLink('PHPUnit example');
    $this->assertRaw('<li class="phpunit-example">');
  }

}
