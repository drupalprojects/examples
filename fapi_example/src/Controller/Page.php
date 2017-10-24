<?php

namespace Drupal\fapi_example\Controller;

use Drupal\examples\Utility\DescriptionTemplateTrait;

/**
 * Simple page controller for drupal.
 */
class Page {

  use DescriptionTemplateTrait;

  /**
   * {@inheritdoc}
   */
  public function getModuleName() {
    return 'fapi_example';
  }

}
