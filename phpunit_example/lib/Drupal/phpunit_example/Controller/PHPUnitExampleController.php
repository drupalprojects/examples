<?php

/**
 * @file
 * Contains \Drupal\phpunit_example\Controller\PHPUnitExampleController.
 */

namespace Drupal\phpunit_example\Controller;

/**
 * Controller routines for filter routes.
 */
class PHPUnitExampleController {

  /**
   * Displays a page with a descriptive page.
   *
   * Our router maps this method to the path 'examples/phpunit_example'.
   */
  function description() {
    $build = array(
      '#markup' => t('<h2>PHPUnit for Drupal: A very basic how-to.</h2>

<h3>How to use this example module</h3>

<p>You really should be reading the various docblocks in the test files.</p>

<h3>Drupalisms</h3>

<ul>
<li><p>Tests belong in their own <code>Drupal\[your_module]\Tests</code> namespace. Under Drupal&#39;s PSR-0 system, this means your PHPUnit-based tests should go in <code>[your_module]/tests/Drupal/[your_module]/Tests/</code>. Modules which are only used for testing purposes should be in <code>[your_module]/tests/modules/[testing_module]/</code></p></li>
<li><p>Your test case should subclass <code>Drupal\Tests\UnitTestCase</code>.</p></li>
<li><p>You can run PHPUnit-based tests from within Drupal 8, by enabling the SimpleTest module and then selecting the PHPUnit group from the testing page. As of this writing, this method doesn&#39;t provide any useful output.</p></li>
</ul>

<h3>Standard PHPUnit Practices</h3>

<p>You can (and really, should) run PHPUnit from the command line. On unix-based systems this means you need to <code>cd core</code> and then <code>./vendor/bin/phpunit</code>.</p>

<p>Also, you should mark your tests as belonging to a group, so they can be run independently. You do this by annotating your test classes with <code>@group GroupName</code>. Currently, no conventions exist for how these groups should be named. You use groups like this: <code>./vendor/bin/phpunit --group GroupName</code>.</p>'),
    );

    return $build;
  }

}
