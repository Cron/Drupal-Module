<?php
/**
 * @file
 * Contains Drupal\cron\Resolver\ShellStrategy.
 */

namespace Drupal\cron\Strategy;

use Cron\Job\ShellJob;
use Cron\Schedule\CrontabSchedule;
use Drupal\cron\Entity\CronJob;

/**
 * Class ShellStrategy
 * @package Drupal\cron\Strategy
 */
class ShellStrategy implements StrategyInterface {

  /**
   * @return string
   */
  public function getType() {
    return 'shell';
  }

  /**
   * @param CronJob $db_job
   *
   * @return bool
   */
  public function handles(CronJob $db_job) {
    return ($db_job->getType() == $this->getType());
  }

  /**
   * @param CronJob $db_job
   *
   * @return ShellJob
   */
  public function buildJob(CronJob $db_job) {
    $job = new ShellJob();
    $job->setCommand($db_job->getCommand(), dirname(DRUPAL_ROOT));
    $job->setSchedule(new CrontabSchedule($db_job->getSchedule()));

    return $job;
  }
}