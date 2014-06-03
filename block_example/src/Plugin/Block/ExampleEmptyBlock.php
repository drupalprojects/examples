<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleEmptyBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\block\Annotation\Block;
use Drupal\block\BlockBase;
use Drupal\Core\Annotation\Translation;

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
   * Implements \Drupal\block\BlockBase::blockBuild().
   */
  public function build() {
    return array();
  }

}
