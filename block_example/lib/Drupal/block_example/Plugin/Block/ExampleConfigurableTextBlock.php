<?php

/**
 * @file
 * Contains \Drupal\block_example\Plugin\Block\ExampleConfigurableTextBlock.
 */

namespace Drupal\block_example\Plugin\Block;

use Drupal\block\Annotation\Block;
use Drupal\block\BlockBase;
use Drupal\Core\Annotation\Translation;

/**
 * Provides a 'Example: configurable text string' block.
 *
 * @Block(
 *   id = "example_configurable_text",
 *   subject = @Translation("Title of first block (example_configurable_text)"),
 *   admin_label = @Translation("Title of first block (example_configurable_text)")
 * )
 */
class ExampleConfigurableTextBlock extends BlockBase {

  /**
   * Overrides \Drupal\block\BlockBase::defaultConfiguration().
   */
  public function defaultConfiguration() {
    return array(
      'block_example_string' => t('A default value. This block was created at %time', array('%time' => date('c'))),
    );
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockForm().
   */
  public function blockForm($form, &$form_state) {
    $form['block_example_string_text'] = array(
      '#type' => 'textfield',
      '#title' => t('Block contents'),
      '#size' => 60,
      '#description' => t('This text will appear in the example block.'),
      '#default_value' => $this->configuration['block_example_string'],
    );
    return $form;
  }

  /**
   * Overrides \Drupal\block\BlockBase::blockSubmit().
   */
  public function blockSubmit($form, &$form_state) {
    $this->configuration['block_example_string'] = $form_state['values']['block_example_string_text'];
  }

  /**
   * Implements \Drupal\block\BlockBase::blockBuild().
   */
  public function build() {
    return array(
      '#type' => 'markup',
      '#markup' => $this->configuration['block_example_string'],
    );
  }

}
