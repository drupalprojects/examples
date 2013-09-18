<?php
/**
 * @file
 * Contains \Drupal\cron_example\Form\CronExampleForm
 */

namespace Drupal\cron_example\Forms;

use Drupal\Core\Form\ConfigFormBase;

/**
 * Form with examples on how to use cron.
 */
class CronExampleForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'cron_example';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, array &$form_state) {
    $config = $this->configFactory->get('examples.cron');

    $form['status'] = array(
      '#type' => 'fieldset',
      '#title' => t('Cron status information'),
    );
    $form['status']['intro'] = array(
      '#type' => 'item',
      '#markup' => t('The cron example demonstrates hook_cron() and hook_queue_info() processing. If you have administrative privileges you can run cron from this page and see the results.'),
    );

    $next_execution = $config->get('next_execution');
    $next_execution = !empty($next_execution) ? $next_execution : time();
    $args = array(
      '%time' => date_iso8601($config->get('next_execution')),
      '%seconds' => $next_execution - time(),
    );
    $form['status']['last'] = array(
      '#type' => 'item',
      '#markup' => t('cron_example_cron() will next execute the first time cron runs after %time (%seconds seconds from now)', $args),
    );

    if (user_access('administer site configuration')) {
      $form['cron_run'] = array(
        '#type' => 'fieldset',
        '#title' => t('Run cron manually'),
      );
      $form['cron_run']['cron_reset'] = array(
        '#type' => 'checkbox',
        '#title' => t("Run cron_example's cron regardless of whether interval has expired."),
        '#default_value' => FALSE,
      );
      $form['cron_run']['cron_trigger'] = array(
        '#type' => 'submit',
        '#value' => t('Run cron now'),
        '#submit' => array(array($this, 'cronRun')),
      );
    }

    $form['cron_queue_setup'] = array(
      '#type' => 'fieldset',
      '#title' => t('Cron queue setup (for hook_cron_queue_info(), etc.)'),
    );
    $queue_1 = \Drupal::queue('cron_example_queue_1');
    $queue_2 = \Drupal::queue('cron_example_queue_2');
    $args = array(
      '%queue_1' => $queue_1->numberOfItems(),
      '%queue_2' => $queue_2->numberOfItems(),
    );
    $form['cron_queue_setup']['current_cron_queue_status'] = array(
      '#type' => 'item',
      '#markup' => t('There are currently %queue_1 items in queue 1 and %queue_2 items in queue 2', $args),
    );
    $form['cron_queue_setup']['num_items'] = array(
      '#type' => 'select',
      '#title' => t('Number of items to add to queue'),
      '#options' => drupal_map_assoc(array(1, 5, 10, 100, 1000)),
      '#default_value' => 5,
    );
    $form['cron_queue_setup']['queue'] = array(
      '#type' => 'radios',
      '#title' => t('Queue to add items to'),
      '#options' => array(
        'cron_example_queue_1' => t('Queue 1'),
        'cron_example_queue_2' => t('Queue 2'),
      ),
      '#default_value' => 'cron_example_queue_1',
    );
    $form['cron_queue_setup']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Add jobs to queue'),
      '#submit' => array(array($this, 'addItems')),
    );

    $form['configuration'] = array(
      '#type' => 'fieldset',
      '#title' => t('Configuration of cron_example_cron()'),
    );
    $form['configuration']['cron_example_interval'] = array(
      '#type' => 'select',
      '#title' => t('Cron interval'),
      '#description' => t('Time after which cron_example_cron will respond to a processing request.'),
      '#default_value' => $config->get('interval'),
      '#options' => array(
        60 => t('1 minute'),
        300 => t('5 minutes'),
        3600 => t('1 hour'),
        86400 => t('1 day'),
      ),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * Allow user to directly execute cron, optionally forcing it.
   */
  public function cronRun(array &$form, array &$form_state) {
    $config = $this->configFactory->get('examples.cron');

    if (!empty($form_state['values']['cron_reset'])) {
      $config->set('next_execution', 0);
    }

    // We don't usually use globals in this way. This is used here only to
    // make it easy to tell if cron was run by this form.
    $GLOBALS['cron_example_show_status_message'] = TRUE;
    if (drupal_cron_run()) {
      drupal_set_message(t('Cron ran successfully.'));
    }
    else {
      drupal_set_message(t('Cron run failed.'), 'error');
    }
  }

  /**
   * Add the items to the queue when signaled by the form.
   */
  public function addItems(array &$form, array &$form_state) {
    $queue = $form_state['values']['queue'];
    $queue_name = $form['cron_queue_setup']['queue'][$queue]['#title'];
    $num_items = $form_state['values']['num_items'];

    $queue = \Drupal::queue($queue);

    for ($i=1; $i <= $num_items; $i++) {
      $item = new \stdClass();
      $item->created = time();
      $item->sequence = $i;
      $queue->createItem($item);
    }

    $args = array(
      '%num' => $num_items,
      '%queue' => $queue_name,
    );
    drupal_set_message(t('Added %num items to %queue', $args));
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, array &$form_state) {
    $this->configFactory->get('examples.cron')
      ->set('interval', $form_state['values']['cron_example_interval'])
      ->save();

    parent::submitForm($form, $form_state);
  }

}
