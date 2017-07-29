<?php

namespace Drupal\Tests\simpletest_example\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Functional tests of the simpletest_example module.
 *
 * @ingroup simpletest_example
 *
 * @group simpletest_example
 * @group examples
 */
class SimpletestExampleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['simpletest_example'];

  /**
   * Verify that we can uninstall and then reinstall simpletest_example.
   *
   * Since simpletest_example installs configuration objects, it needs to clean
   * up after itself. This test verifies that it does.
   *
   * @see https://www.drupal.org/node/2841840
   */
  public function testUninstallReinstall() {
    $session = $this->assertSession();

    // The simpletest_example module should have been installed by the test, so
    // we can just uninstall it.
    /* @var $module_installer \Drupal\Core\Extension\ModuleInstallerInterface */
    $module_installer = $this->container->get('module_installer');
    $module_installer->uninstall(['simpletest_example']);
    $this->drupalGet('examples/simpletest-example');
    $session->statusCodeEquals(404);

    // We reinstall the simpletest_example module to make sure it happens
    // properly.
    $module_installer->install(['simpletest_example']);
    $this->drupalGet('examples/simpletest-example');
    $session->statusCodeEquals(200);
  }

}
