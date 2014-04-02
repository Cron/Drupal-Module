<?php

/**
 * @file
 * Drush run cron job functionality.
 */

namespace Drupal\cron\Command;

use Cron\Cron;
use Cron\Executor\Executor;

/**
 * Class CronRunCommand
 * @package Drupal\cron\Command
 */
class CronRunCommand extends CronCommandBase {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:run'] = array(
      'description' => dt('Runs any currently scheduled cron job.'),
      'callback' => 'cron_drush_run',
      'arguments' => array(
        'job' => 'Run only this job (if enabled)',
      ),
      'required-arguments' => FALSE,
      'options' => array(
        'force' => 'Run the specified job, regardless the state of the cron job.',
      ),
    );
  }

  /**
   * Enable the cron job.
   */
  public function execute($job = NULL) {
//    $force = drush_get_option('force', FALSE);
    $force = FALSE;

    $cron = new Cron();
    $cron->setExecutor(new Executor());

    $resolver = \Drupal::service('cron_shell_resolver');
    if (!is_null($job)) {
      $db_job = \Drupal::service('cron_job_manager')->loadByName($job);
      if (!$db_job) {
        drush_set_error('cron', dt('The specified job does not exist.'));
        return;
      }
      $resolver->setJob($db_job);
    }
    $resolver->setForce($force);
    $cron->setResolver($resolver);

    $time = microtime(true);
    $report = $cron->run($job, $force);

    while ($cron->isRunning()) {}

    dsm(t('time: !time', array('!time' => microtime(true) - $time)));
//    drush_log(dt('time: !time', array('!time' => microtime(true) - $time)), 'ok');
  }
}
