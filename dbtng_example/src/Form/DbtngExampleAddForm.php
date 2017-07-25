<?php

namespace Drupal\dbtng_example\Form;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\dbtng_example\DbtngExampleStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form to add a database entry, with all the interesting fields.
 */
class DbtngExampleAddForm implements FormInterface, ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * The current user.
   *
   * We'll need this service in order to check if the user is logged in.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   *
   * We'll use the ContainerInjectionInterface pattern here to inject the
   * current user and also get the string_translation service.
   */
  public static function create(ContainerInterface $container) {
    $form = new static(
      $container->get('current_user')
    );
    // The StringTranslationTrait trait manages the string translation service
    // for us. We can inject the service here.
    $form->setStringTranslation($container->get('string_translation'));
    return $form;
  }

  /**
   * Construct the new form object.
   */
  public function __construct(AccountProxyInterface $current_user) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'dbtng_add_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = array();

    $form['message'] = array(
      '#markup' => $this->t('Add an entry to the dbtng_example table.'),
    );

    $form['add'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Add a person entry'),
    );
    $form['add']['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#size' => 15,
    );
    $form['add']['surname'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Surname'),
      '#size' => 15,
    );
    $form['add']['age'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Age'),
      '#size' => 5,
      '#description' => $this->t("Values greater than 127 will cause an exception. Try it - it's a great example why exception handling is needed with DTBNG."),
    );
    $form['add']['submit'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Add'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Verify that the user is logged-in.
    if ($this->currentUser->isAnonymous()) {
      $form_state->setError($form['add'], $this->t('You must be logged in to add values to the database.'));
    }
    // Confirm that age is numeric.
    if (!intval($form_state->getValue('age'))) {
      $form_state->setErrorByName('age', $this->t('Age needs to be a number'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Gather the current user so the new record has ownership.
    $account = $this->currentUser;
    // Save the submitted entry.
    $entry = array(
      'name' => $form_state->getValue('name'),
      'surname' => $form_state->getValue('surname'),
      'age' => $form_state->getValue('age'),
      'uid' => $account->id(),
    );
    $return = DbtngExampleStorage::insert($entry);
    if ($return) {
      drupal_set_message($this->t('Created entry @entry', array('@entry' => print_r($entry, TRUE))));
    }
  }

}
