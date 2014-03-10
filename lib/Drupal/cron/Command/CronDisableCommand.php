<?php

/**
 * @file
 * Drush disable cron job functionality.
 */

namespace Drupal\cron\Command;

/**
 * Class CronDisableCommand
 * @package Drupal\cron\Command
 */
class CronDisableCommand extends CronCommandBase {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:disable'] = array(
      'description' => dt('Disable a cron job.'),
      'callback' => 'cron_drush_disable',
      'arguments' => array(
        'job' => 'The job to disable.',
      ),
      'required-arguments' => TRUE,
    );
  }

  /**
   * Disable the cron job.
   */
  public function execute($name) {
    $job = $this->queryJob($name);

    if (!$job) {
      drush_set_error('cron', dt('The specified job does not exist.'));
      return;
    }

    $job->setEnabled(FALSE);
    $job->save();

    drush_log(dt('Cron "!name" was disabled successfully!', array('!name' => $job->getName())), 'ok');
  }

  /**
   * Get CronJob entity from name.
   *
   * @param string $name
   *
   * @return array
   */
  protected function queryJob($name) {
    return $this->container()->get('cron_job_manager')
      ->loadByName($name);
  }
}
