<?php

namespace Drupal\Tests\ajax_example\FunctionalJavascript;

use Drupal\Core\Url;
use Drupal\FunctionalJavascriptTests\JavascriptTestBase;

/**
 * Test the user interactions for the Simplest example.
 *
 * @group ajax_example
 */
class SimplestTest extends JavascriptTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['ajax_example'];

  /**
   * Test the user interactions for the Autotextfields example.
   */
  public function testAutotextfields() {
    // Get our Mink stuff.
    $session = $this->getSession();
    $page = $session->getPage();
    $assert = $this->assertSession();

    // Get the page.
    $form_url = Url::fromRoute('ajax_example.simplest');
    $this->drupalGet($form_url);

    // Don't repeat ourselves. This makes it easier if we change the markup
    // later.
    $description_selector = '#replace-textfield-container div.description';

    // Check our initial state.
    $assert->elementExists('css', '#replace-textfield-container');
    $assert->elementNotExists('css', $description_selector);

    // Select values on the dropdown. Start with three so the change event is
    // triggered.
    foreach (['three', 'two', 'one'] as $value) {
      // Select the dropdown value.
      $page->selectFieldOption('changethis', $value);
      // Wait for AJAX to happen.
      $assert->assertWaitOnAjaxRequest();
      // Assert that the description exists.
      $assert->elementExists('css', $description_selector);
      // Get the description element from the page.
      $prompt_element = $page->find('css', $description_selector);
      // Assert that the description element says what we expect it to say.
      $this->assertEquals(
        "Say why you chose '$value'",
        $prompt_element->getText()
      );
    }
  }

}
