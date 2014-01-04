<?php

/**
 * @file
 * Regression tests for tour_example module.
 */

namespace Drupal\tour_example\Tests;

use Drupal\tour\Tests\TourTestBasic;

/**
 * Regression tests for the tour_example module.
 *
 * @ingroup tour_example
 */
class TourExampleTest extends TourTestBasic {

  public static $modules = array('tour_example');

  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => 'Tour Example Tests',
      'description' => 'Regression tests for tour_example module.',
      'group' => 'Examples',
    );
  }

  /**
   * Overloading testTips() to verify that the tour exists.
   *
   * We overload TourTestBasic::testTips() in order to verify the existence of
   * the tour tips we created.
   *
   * We also have the option of calling assertToolTips() in a more direct
   * fashion, but we won't do that here.
   *
   * @see Drupal\tour\Tests\TourTestBase::assertToolTips()
   */
  public function testTips() {
    $this->tips = array(
      'examples/tour_example' => array(
        array('data-id' => 'tour-id-1'),
        array('data-id' => 'tour-id-2'),
        array('data-id' => 'tour-id-3'),
        array('data-id' => 'tour-id-4'),
      ),
    );
    parent::testTips();
  }

}
