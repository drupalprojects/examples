<?php

/**
 * @file
 * Contains \Drupal\page_example\Controller\BlockExampleController.
 */

namespace Drupal\block_example\Controller;

/**
 * Controller routines for block example routes.
 */
class BlockExampleController {

  /**
   * A simple controller method to explain what the block example is about.
   */
  public function description() {
    $build = array(
      '#markup' => t('The Block Example provides three sample blocks which demonstrate the various block APIs. To experiment with the blocks, enable and configure them on <a href="@url">the block admin page</a>.', array('@url' => url('admin/structure/block'))),
    );

    return $build;
  }

}
