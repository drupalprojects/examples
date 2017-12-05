<?php

namespace Drupal\Tests\ajax_example\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Verify functionalities of ajax_example.
 *
 * @group ajax_example
 * @group examples
 *
 * @ingroup ajax_example
 */
class AjaxExampleMenuTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['ajax_example'];

  /**
   * The installation profile to use with this test.
   *
   * @var string
   */
  protected $profile = 'minimal';

  /**
   * Tests links.
   */
  public function testAjaxExampleLinks() {
    // Login a user that can access content.
    $this->drupalLogin(
      $this->createUser(['access content', 'access user profiles'])
    );

    $assertion = $this->assertSession();

    // Routes with menu links, and their form buttons.
    $routes_with_menu_links = [
      'ajax_example.description' => [],
      'ajax_example.simplest' => [],
      'ajax_example.autotextfields' => ['Click Me'],
      'ajax_example.submit_driven_ajax' => ['Submit'],
      'ajax_example.dependent_dropdown' => ['Submit'],
      'ajax_example.dynamic_form_sections' => ['Choose'],
      'ajax_example.wizard' => ['Next step'],
      'ajax_example.wizardnojs' => ['Next step'],
      'ajax_example.ajax_link_render' => [],
      'ajax_example.autocomplete_user' => ['Submit'],
    ];

    // Ensure the links appear in the tools menu sidebar.
    $this->drupalGet('');
    foreach (array_keys($routes_with_menu_links) as $route) {
      $assertion->linkByHrefExists(Url::fromRoute($route)->getInternalPath());
    }

    // All our routes with their form buttons.
    $routes = [
      'ajax_example.ajax_link_callback' => [],
    ];

    // Go to all the routes and click all the buttons.
    $routes = array_merge($routes_with_menu_links, $routes);
    foreach ($routes as $route => $buttons) {
      $path = Url::fromRoute($route);
      $this->drupalGet($path);
      $assertion->statusCodeEquals(200);
      foreach ($buttons as $button) {
        $this->drupalPostForm($path, [], $button);
        $assertion->statusCodeEquals(200);
      }
    }
  }

}
