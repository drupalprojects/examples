<?php

namespace Drupal\Tests\tour_example\Functional;

use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Base class for testing Tour functionality.
 */
abstract class TourTestBase extends ExamplesBrowserTestBase {

  /**
   * Assert whether tour tips have corresponding page elements.
   *
   * @param array $tips
   *   A list of tips which provide either a "data-id" or "data-class".
   *
   * @code
   * // Basic example.
   * $this->assertTourTips();
   *
   * // Advanced example. The following would be used for multipage or
   * // targeting a specific subset of tips.
   * $tips = array();
   * $tips[] = array('data-id' => 'foo');
   * $tips[] = array('data-id' => 'bar');
   * $tips[] = array('data-class' => 'baz');
   * $this->assertTourTips($tips);
   * @endcode
   *
   * @todo Force the caller to provide data rather than searching for it and
   *   then asserting that the elements we found do exist.
   * @see https://www.drupal.org/project/examples/issues/2940089
   */
  public function assertTourTips(array $tips = []) {
    $assert = $this->assertSession();
    // Get the rendered tips and their data-id and data-class attributes.
    if (empty($tips)) {
      // Tips are rendered as <li> elements inside <ol id="tour">.
      $rendered_tips = $this->xpath('//ol[@id = "tour"]//li[starts-with(@class, "tip")]');
      foreach ($rendered_tips as $rendered_tip) {
        $item = [];
        if ($rendered_tip->hasAttribute('data-id')) {
          $item['data-id'] = $rendered_tip->getAttribute('data-id');
        }
        if ($rendered_tip->hasAttribute('data-class')) {
          $item['data-class'] = $rendered_tip->getAttribute('data-class');
        }
        if (!empty($item)) {
          $tips[] = $item;
        }
      }
    }

    // If the tips are still empty we need to fail.
    if (empty($tips)) {
      $this->fail('Could not find tour tips on the current page.');
    }
    else {
      // Check for corresponding page elements.
      $page = $this->getSession()->getPage();
      foreach ($tips as $tip) {
        if (!empty($tip['data-id'])) {
          $this->assertNotNull($page->find('css', "#{$tip['data-id']}"));
        }
        elseif (!empty($tip['data-class'])) {
          $this->assertNotNull($page->find('css', "#{$tip['data-class']}"));
        }
      }
    }
  }

}
