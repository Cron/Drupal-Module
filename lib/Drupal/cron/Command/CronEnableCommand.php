<?php

/**
 * @file
 * Drush enable cron job functionality.
 */

namespace Drupal\cron\Command;

/**
 * Class CronEnableCommand
 * @package Drupal\cron\Command
 */
class CronEnableCommand extends CronCommandBase {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:enable'] = array(
      'description' => dt('Enable a cron job.'),
      'callback' => 'cron_drush_enable',
      'arguments' => array(
        'job' => 'The job to enable.',
      ),
      'required-arguments' => TRUE,
    );
  }

  /**
   * Enable the cron job.
   */
  public function execute($name) {
    $job = $this->queryJob($name);

    if (!$job) {
      drush_set_error('cron', dt('The specified job does not exist.'));
      return;
    }

    $job->setEnabled(TRUE);
    $job->save();

    drush_log(dt('Cron "!name" was enabled successfully!', array('!name' => $job->getName())), 'ok');
  }

  /**
   * Get CronJob entity from name.
   *
   * @param string $name
   *
   * @return \Drupal\cron\Entity\CronJob
   */
  protected function queryJob($name) {
    return \Drupal::service('cron_job_manager')
      ->loadByName($name);
  }
}
