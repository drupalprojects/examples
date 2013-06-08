<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleEmptyBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\block\BlockBase;
use Drupal\Core\Annotation\Translation;
use Drupal\Component\Annotation\Plugin;

/**
 * Provides a 'Example: empty block' block.
 *
 * @Plugin(
 *   id = "example_empty",
 *   subject = @Translation("Example: empty block"),
 *   admin_label = @Translation("Example: empty block"),
 *   module = "block_example"
 * )
 */
class ExampleEmptyBlock extends BlockBase {

  /**
   * Implements \Drupal\block\BlockBase::blockBuild().
   */
  public function blockBuild() {
    return array();
  }

}
