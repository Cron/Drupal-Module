<?php

/**
 * @file
 * Drush delete cron job functionality.
 */

namespace Drupal\cron\Command;

/**
 * Class CronDeleteCommand
 * @package Drupal\cron\Command
 */
class CronDeleteCommand extends CronCommandBase {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:delete'] = array(
      'description' => dt('Delete a cron job.'),
      'callback' => 'cron_drush_delete',
      'arguments' => array(
        'job' => 'The job to delete.',
      ),
      'required-arguments' => TRUE,
    );
  }

  /**
   * Delete the cron job.
   */
  public function execute($name) {
    $job = $this->queryJob($name);

    if (!$job) {
      drush_set_error('cron', dt('The specified job does not exist.'));
      return;
    }

    if ($job->getEnabled()) {
      drush_set_error('cron', dt('The job should be disabled first.'));
      return;
    }

    if (!drush_confirm(dt('You are about to delete "!name", are you sure?', array('!name' => $name)))) {
      return drush_user_abort();
    }

    $job->delete();

    drush_log(dt('Cron "!name" was deleted successfully!', array('!name' => $name)), 'ok');
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
