<?php

namespace Drupal\Tests\testing_example\Unit\Controller;

use Drupal\Tests\UnitTestCase;
use Drupal\testing_example\Controller\ContrivedController;

/**
 * @group testing_example
 */
class ContrivedControllerTest extends UnitTestCase {

  public function provideTestAdd() {
    return [
      [4, 2, 2],
      [0, NULL, '']
    ];
  }

  /**
   * @dataProvider provideTestAdd
   */
  public function testAdd($expected, $first, $second) {
    $controller = $this->getMockBuilder(ContrivedController::class)
      ->disableOriginalConstructor()
      ->getMock();
    $ref_add = new \ReflectionMethod($controller, 'add');
    $ref_add->setAccessible(TRUE);
    $this->assertEquals($expected, $ref_add->invokeArgs($controller, [$first, $second]));
  }

}
