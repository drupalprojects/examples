<?php

/**
 * @file
 * Contains \Drupal\phpunit_example\Controller\PHPUnitExampleController.
 */

namespace Drupal\phpunit_example\Controller;

/**
 * Controller for PHPUnit description page.
 */
class PHPUnitExampleController {

  /**
   * Displays a page with a descriptive page.
   *
   * Our router maps this method to the path 'examples/phpunit_example'.
   */
  public function description() {
    $build = array(
      '#markup' => t('<h2>PHPUnit for Drupal: A very basic how-to.</h2>
<h3>How to use this example module</h3>
<p>You really should be reading the various docblocks in the test files.</p>
<h3>How To:</h3>
<ul>
<li><p>PHPUnit tests belong in their own directory, so they won&#39;t be loaded by the autoloader during normal bootstrap. This means you should have a <code>/tests</code> directory in the root of your module directory.</p></li>
<li><p>Your tests should be in the <code>Drupal\[your_module]\Tests</code> namespace. Under Drupal&#39;s PSR-0 system, this means your PHPUnit-based tests should go in <code>[your_module]/tests/Drupal/[your_module]/Tests/</code>.</p></li>
<li><p>Your test case should subclass <code>Drupal\Tests\UnitTestCase</code>.</p></li>
<li><p>You can run PHPUnit-based tests from within Drupal 8 by enabling the Testing module and then selecting the PHPUnit group from the testing page. As of this writing, this method doesn&#39;t provide any useful output.</p></li>
</ul>
<h3>Standard PHPUnit Practices</h3>
<p>You can (and really, should) run PHPUnit from the command line. On unix-based systems this means you need to <code>cd core</code> and then <code>./vendor/bin/phpunit</code>.</p>
<p>Also, you should mark your tests as belonging to a group, so they can be run independently. You do this by annotating your test classes with <code>@group GroupName</code>. Currently, no conventions exist for how these groups should be named, but your module machine name is a likely candidate. You use groups like this: <code>./vendor/bin/phpunit --group GroupName</code>.</p>
<p>So, for instance, to run all of the PHPUnit example tests, you would type <code>./vendor/bin/phpunit --group phpunit_example</code>.</p>
<p>As you can see, including a <code>@group</code> annotation is a good idea.</p>'),
    );

    return $build;
  }

}
