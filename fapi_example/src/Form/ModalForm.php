<?php

namespace Drupal\fapi_example\Form;

use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\EventSubscriber\MainContentViewSubscriber;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements the ModalForm form controller.
 *
 * This example demonstrates implementation of a form that is designed to be
 * used as a modal form.  To properly display the modal the link presented by
 * the \Drupal\fapi_example\Controller\Page page controller loads the Drupal
 * dialog and ajax libraries.  The submit handler in this class returns ajax
 * commands to replace text in the calling page after submission .
 *
 * @see \Drupal\Core\Form\FormBase
 */
class ModalForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Create a new form object and inject its services.
    $form = new static();
    $form->setRequestStack($container->get('request_stack'));
    $form->setStringTranslation($container->get('string_translation'));
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'fapi_example_modal_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Figure out if this form is being built in a modal context.
    $is_modal = $this->getRequest()->query->get(MainContentViewSubscriber::WRAPPER_FORMAT) == 'drupal_modal';

    $form['description'] = [
      '#type' => 'item',
      '#markup' => $this->t('This example demonstrates a modal form. The submit handler will be an AJAX dialog.'),
    ];
    // Add a link to show this form in a modal dialog if we're not already in
    // one.
    if (!$is_modal) {
      $form['use_ajax_container'] = [
        '#type' => 'container',
        '#weight' => -999,
      ];
      $form['use_ajax_container']['use_ajax'] = [
        '#type' => 'link',
        '#title' => $this->t('See this form as a modal.'),
        '#url' => Url::fromRoute('fapi_example.modal_form'),
        '#attributes' => ['class' => ['use-ajax'], 'data-dialog-type' => 'modal'],
      ];
    }

    // This element is responsible for displaying form errors.
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#required' => TRUE,
    ];

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // Add a submit button that handles the submission of the form.
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => [
        'callback' => '::ajaxSubmitForm',
        'event' => 'click',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('title');
    $message = $this->t('Submit handler: You specified a title of @title.', ['@title' => $title]);
    drupal_set_message($message);
  }

  /**
   * Implements the submit handler for the ajax call.
   *
   * @param array $form
   *   Render array representing from.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Current form state.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Array of ajax commands to execute on submit of the modal form.
   */
  public function ajaxSubmitForm(array &$form, FormStateInterface $form_state) {
    // We begin building a new ajax reponse.
    $response = new AjaxResponse();

    // At this point the submit handler has fired. Clear the message set by the
    // submit handler.
    drupal_get_messages();

    // Handle errors.
    if ($form_state->getErrors()) {
      $form['status_messages'] = [
        '#type' => 'status_messages',
        '#weight' => -10,
      ];
      $response->addCommand(new OpenModalDialogCommand($this->t('Errors'), $form));
    }
    else {
      // We use FormattableMarkup to handle sanitizing the input.
      $title = new FormattableMarkup(':title', [':title' => $form_state->getValue('title')]);
      // Construct a message for the modal dialog.
      $message = $this->t('Your specified title of \'%title\' appears in this modal dialog.', ['%title' => $title]);
      // This will be the contents for the modal dialog.
      $content = [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $message,
      ];
      // We can specify jQuery UI dialog options.
      $options = [
        // This CSS class does not exist, but we're adding it here to
        // demonstrate how.
        'dialogClass' => 'fapi-example-modal-dialog',
        // Make the dialog bigger than default.
        'width' => '50%',
      ];
      // Add the OpenModalDialogCommand to the response. This will cause Drupal
      // AJAX to show the modal dialog. The user can click the little X to close
      // the dialog.
      $response->addCommand(new OpenModalDialogCommand($title, $content, $options));
    }

    // We have to attach the core dialog JavaScript to our response in order for
    // it to work.
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    $response->setAttachments($form['#attached']);
    return $response;
  }

}
