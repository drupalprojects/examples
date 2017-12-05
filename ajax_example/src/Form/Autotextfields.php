<?php

namespace Drupal\ajax_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Show/hide textfields based on AJAX-enabled checkbox clicks.
 */
class Autotextfields extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_example_autotextfields';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['ask_first_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Ask me my first name'),
      '#ajax' => [
        'callback' => '::prompt',
        'wrapper' => 'textfields',
        'effect' => 'fade',
      ],
    ];
    $form['ask_last_name'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Ask me my last name'),
      '#ajax' => [
        'callback' => '::prompt',
        'wrapper' => 'textfields',
        'effect' => 'fade',
      ],
    ];

    $form['textfields'] = [
      '#title' => $this->t("Generated text fields for first and last name"),
      '#prefix' => '<div id="textfields">',
      '#suffix' => '</div>',
      '#type' => 'fieldset',
      '#description' => t('This is where we put automatically generated textfields'),
    ];

    // Since checkboxes return TRUE or FALSE, we have to check that
    // $form_state has been filled as well as what it contains.
    if (!empty($form_state->getValue('ask_first_name')) && $form_state->getValue('ask_first_name')) {
      $form['textfields']['first_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('First Name'),
      ];
    }
    if (!empty($form_state->getValue('ask_last_name')) && $form_state->getValue('ask_last_name')) {
      $form['textfields']['last_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Last Name'),
      ];
    }

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Click Me'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * Callback for autotextfields.
   *
   * Selects the piece of the form we want to use as replacement text and
   * returns it as a form (renderable array).
   */
  public function prompt($form, FormStateInterface $form_state) {
    return $form['textfields'];
  }

}
