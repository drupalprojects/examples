<?php

namespace Drupal\simpletest_example\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Controller for Simpletest description page.
 */
class SimpleTestExampleController {

  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  protected function getModuleName() {
    return 'simpletest_example';
  }

}
