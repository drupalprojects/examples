<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleEmptyBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Example: empty block' block.
 *
 * @Block(
 *   id = "example_empty",
 *   subject = @Translation("Example: empty block"),
 *   admin_label = @Translation("Example: empty block")
 * )
 */
class ExampleEmptyBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return array();
  }

}
