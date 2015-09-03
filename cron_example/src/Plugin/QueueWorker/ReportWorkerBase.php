<?php

/**
 * @file
 * Contains \Drupal\cron_example\Plugin\QueueWorker\ReportWorkerBase.
 */

namespace Drupal\cron_example\Plugin\QueueWorker;

use Drupal\Core\Queue\QueueWorkerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;


/**
 * Provides base functionality for the ReportWorkers.
 */
abstract class ReportWorkerBase extends QueueWorkerBase {

  use StringTranslationTrait;

  /**
   * Simple reporter log and display information about the queue.
   *
   * @param int $worker
   *   Worker number.
   * @param object $item
   *   The $item which was stored in the cron queue.
   */
  protected function reportWork($worker, $item) {
    if (\Drupal::state()->get('cron_example_show_status_message')) {
      drupal_set_message(
        $this->t('Queue @worker worker processed item with sequence @sequence created at @time', [
        '@worker' => $worker,
        '@sequence' => $item->sequence,
        '@time' => date_iso8601($item->created),
          ]
        )
      );
    }
    \Drupal::logger('cron_example')->info('Queue @worker worker processed item with sequence @sequence created at @time', [
      '@worker' => $worker,
      '@sequence' => $item->sequence,
      '@time' => date_iso8601($item->created),
      ]
    );
  }

}
