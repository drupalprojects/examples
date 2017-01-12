<?php

namespace Drupal\Tests\events_example\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the functionality of the Events Example module.
 *
 * For another example of testing whether or not events are dispatched see
 * \Drupal\Tests\migrate\Kernel\MigrateEventsTest.
 *
 * @ingroup events_example
 * @group examples
 */
class EventsExampleTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['events_example'];

  /**
   * {@inheritdoc}
   */
  protected $profile = 'minimal';

  /**
   * Test the output of the example page.
   */
  public function testEventsExample() {
    // Test that the main page for the example is accessible.
    $this->drupalGet('examples/events-example');
    $this->assertSession()->statusCodeEquals(200);

    // Verify the page contains the required form fields.
    $this->assertSession()->fieldExists('incident_type');
    $this->assertSession()->fieldExists('incident');

    // Submit the form with an incident type of 'stolen_princess'. This does a
    // couple of things. Fist of all, it ensures that our code in
    // EventsExampleForm::submitForm() that dispatches events works. If it did
    // not work, no event would be dispatched, and the message below would never
    // get displayed. Secondly, it tests that our
    // EventsExampleSubscriber::notifyMario() event subscriber is triggered for
    // incidents of the type 'stolen_princess'.
    $values = [
      'incident_type' => 'stolen_princess',
      'incident' => $this->randomString(),
    ];
    $this->drupalPostForm('examples/events-example', $values, 'Submit');
    $this->assertSession()->pageTextContains('Mario has been alerted. Thank you.');

    // Fill out the form again, this time testing that the
    // EventsExampleSubscriber::notifyBatman() subscriber is working.
    $values = [
      'incident_type' => 'joker',
      'incident' => $this->randomString(),
    ];
    $this->drupalPostForm('examples/events-example', $values, 'Submit');
    $this->assertSession()->pageTextContains('Batman has been alerted. Thank you.');
  }

}
