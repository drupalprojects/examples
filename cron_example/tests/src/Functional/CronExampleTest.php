<?php

namespace Drupal\Tests\cron_example\Functional;

use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Test the functionality for the Cron Example.
 *
 * @ingroup cron_example
 *
 * @group cron_example
 * @group examples
 */
class CronExampleTest extends ExamplesBrowserTestBase {

  /**
   * An editable config object for access to 'cron_example.settings'.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $cronConfig;

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['cron_example', 'node'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    // Create user. Search content permission granted for the search block to
    // be shown.
    $this->drupalLogin($this->drupalCreateUser(['administer site configuration', 'access content']));

    $this->cronConfig = \Drupal::configFactory()->getEditable('cron_example.settings');
  }

  /**
   * Create an example node, test block through admin and user interfaces.
   */
  public function testCronExampleBasic() {
    $assert = $this->assertSession();

    // Pretend that cron has never been run (even though simpletest seems to
    // run it once...).
    \Drupal::state()->set('cron_example.next_execution', 0);
    $this->drupalGet('examples/cron-example');

    // Initial run should cause cron_example_cron() to fire.
    $post = [];
    $this->drupalPostForm('examples/cron-example', $post, t('Run cron now'));
    $assert->pageTextContains('cron_example executed at');

    // Forcing should also cause cron_example_cron() to fire.
    $post['cron_reset'] = TRUE;
    $this->drupalPostForm(NULL, $post, t('Run cron now'));
    $assert->pageTextContains('cron_example executed at');

    // But if followed immediately and not forced, it should not fire.
    $post['cron_reset'] = FALSE;
    $this->drupalPostForm(NULL, $post, t('Run cron now'));
    $assert->statusCodeEquals(200);
    $assert->pageTextNotContains('cron_example executed at');
    $assert->pageTextContains('There are currently 0 items in queue 1 and 0 items in queue 2');

    $post = [
      'num_items' => 5,
      'queue' => 'cron_example_queue_1',
    ];
    $this->drupalPostForm(NULL, $post, t('Add jobs to queue'));
    $assert->pageTextContains('There are currently 5 items in queue 1 and 0 items in queue 2');

    $post = [
      'num_items' => 100,
      'queue' => 'cron_example_queue_2',
    ];
    $this->drupalPostForm(NULL, $post, t('Add jobs to queue'));
    $assert->pageTextContains('There are currently 5 items in queue 1 and 100 items in queue 2');

    $this->drupalPostForm('examples/cron-example', [], t('Run cron now'));
    $assert->responseMatches('/Queue 1 worker processed item with sequence 5 /');
    $assert->responseMatches('/Queue 2 worker processed item with sequence 100 /');
  }

}
