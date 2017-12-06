<?php

namespace Drupal\ajax_example\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

/**
 * AJAX example wizard.
 */
class Wizard extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_example_wizard';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $no_js_use = FALSE) {
    $url = Url::fromUri('internal:/examples/ajax-example/wizard-nojs');
    $link = Link::fromTextAndUrl($this->t('examples/ajax-example/wizard-nojs'), $url)
      ->toString();

    // Prepare link for multiple arguments.
    $urltwo = Url::fromUri('internal:/examples/ajax-example/wizard');
    $linktwo = Link::fromTextAndUrl($this->t('examples/ajax-example/wizard'), $urltwo)
      ->toString();

    // We want to deal with hierarchical form values.
    $form['#tree'] = TRUE;
    $form['description'] = [
      '#markup' => t('This example is a step-by-step wizard. The @link does it without page reloads; the @link1 is the same code but simulates a non-javascript environment, showing it with page reloads.', [
        '@link' => $linktwo,
        '@link1' => $link,
      ]),
    ];

    $form['step'] = [
      '#type' => 'hidden',
      '#value' => !empty($form_state->getValue('step')) ? $form_state->getValue('step') : 1,
    ];
    print_r($form_state->getValue('step'));

    if ($form['step']['#value'] == 1) {
      $form['step1'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Step 1: Personal details'),
      ];
      $form['step1']['name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Your name'),
        '#default_value' => empty($form_state->getValue([
          'step1',
          'name',
        ]) ? '' : $form_state->getValue(['step1', 'name'])),
        '#required' => TRUE,
      ];

      $form['next'] = [
        '#type' => 'submit',
        '#value' => $this->t('Next step'),
        '#ajax' => [
          'wrapper' => 'ajax-example-wizard',
          'callback' => '::prompt',
        ],
      ];
    }

    // This simply allows us to demonstrate no-javascript use without
    // actually turning off javascript in the browser. Removing the #ajax
    // element turns off AJAX behaviors on that element and as a result
    // ajax.js doesn't get loaded.
    // For demonstration only! You don't need this.
    if ($no_js_use) {
      // Remove the #ajax from the above, so ajax.js won't be loaded.
      // For demonstration only.
      unset($form['next']['#ajax']);
      unset($form['prev']['#ajax']);
    }

    return $form;
  }

  /**
   * Wizard callback function.
   *
   * @param array $form
   *   Form API form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Form API form.
   *
   * @return array
   *   Form array.
   */
  public function prompt(array $form, FormStateInterface $form_state) {
    return $form;
  }

  /**
   * Save away the current information.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if ($form_state->getTriggeringElement()['#value'] == $this->t('Submit your information')) {
      $value_message = $this->t('Your information has been submitted:') . ' ';
      foreach ($form_state->getValue('value') as $step => $values) {
        $value_message .= "$step: ";
        foreach ($values as $key => $value) {
          $value_message .= "$key=$value, ";
        }
      }
      drupal_set_message($value_message);
      $form_state->setRebuild(FALSE);
      // Redirect to #action, else return.
      return;
    }
    else {
      $step = $form_state->getValue('step');
      // Increment or decrement the step as needed. Recover values if they
      // exist.
      if ($form_state->getTriggeringElement()['#value']->__toString() == $this->t('Next step')) {
        $step++;
      }
      elseif ($form_state->getTriggeringElement()['#value']->__toString() == $this->t('Previous step')) {
        $step--;
      }

      switch ($step) {
        case 1:
          $form['step1'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Step 1: Personal details'),
          ];
          $form['step1']['name'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Your name'),
            '#default_value' => empty($form_state->getValue([
              'step1',
              'name',
            ]) ? '' : $form_state->getValue(['step1', 'name'])),
            '#required' => TRUE,
          ];
          $form_state->setValue('step', 1);
          break;

        case 2:
          unset($form['step1']);
          unset($form['next']);
          $form['step2'] = [
            '#type' => 'fieldset',
            '#title' => t('Step 2: Street address info'),
          ];
          $form['step2']['address'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Your street address'),
            '#default_value' => empty($form_state->getValue([
              'step2',
              'address',
            ]) ? '' : $form_state->getValue(['step2', 'address'])),
            '#required' => TRUE,
          ];
          $form_state->setValue('step', $step);
          break;

        case 3:

          $form['step3'] = [
            '#type' => 'fieldset',
            '#title' => $this->t('Step 3: City info'),
          ];
          $form['step3']['city'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Your city'),
            '#default_value' => empty($form_state->getValue([
              'step3',
              'city',
            ]) ? '' : $form_state->getValue(['step3', 'city'])),
            '#required' => TRUE,
          ];
          $form_state->setValue('step', $step);
          break;
      }
      if ($step == 3) {

        $form['submit'] = [
          '#type' => 'submit',
          '#value' => $this->t("Submit your information"),
        ];
      }
      if ($step > 1 && !isset($form['prev'])) {
        $form['prev'] = [
          '#type' => 'submit',
          '#value' => t("Previous step"),
          // Since all info will be discarded, don't validate on 'prev'.
          '#limit_validation_errors' => [],
          // #submit is required to use #limit_validation_errors.
          '#submit' => ['ajax_example_wizard_submit'],
          '#ajax' => [
            'wrapper' => 'ajax-example-wizard',
            'callback' => '::prompt',
          ],
        ];
      }
      if ($step < 3 && !isset($form['next'])) {
        $form['next'] = [
          '#type' => 'submit',
          '#value' => $this->t('Next step'),
          '#limit_validation_errors' => [],
          '#ajax' => [
            'wrapper' => 'ajax-example-wizard',
            'callback' => '::prompt',
          ],
        ];
      }
      $response = new AjaxResponse();
      $response->addCommand(new HtmlCommand('#ajax-example-wizard', $form));
      return $response;
    }

  }

}
