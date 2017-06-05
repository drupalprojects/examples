<?php

namespace Drupal\Tests\tour_example\Functional;

use Drupal\Tests\examples\Functional\ExamplesBrowserTestBase;

/**
 * Base class for testing Tour functionality.
 *
 * @todo: When tour module's TourTestBase is updated to phpunit, we can remove
 *        this class and use that one instead.
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
   */
  public function assertTourTips(array $tips = array()) {
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
      $total = 0;
      $modals = 0;
      foreach ($tips as $tip) {
        if (!empty($tip['data-id'])) {
          $elements = $this->getSession()->getPage()->find('css', "#{$tip['data-id']}");
          $this->assertTrue(!empty($elements) && count($elements) === 1, format_string('Found corresponding page element for tour tip with id #%data-id', array('%data-id' => $tip['data-id'])));
        }
        elseif (!empty($tip['data-class'])) {
          $elements = $this->getSession()->getPage()->find('css', "#{$tip['data-class']}");

          $this->assertFalse(empty($elements), format_string('Found corresponding page element for tour tip with class .%data-class', array('%data-class' => $tip['data-class'])));
        }
        else {
          // It's a modal.
          $modals++;
        }
        $total++;
      }
      $this->verbose(format_string('Total %total Tips tested of which %modals modal(s).', array('%total' => $total, '%modals' => $modals)));
    }
  }

}
