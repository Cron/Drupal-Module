<?php
/**
 * @file
 * Contains Drupal\cron\Resolver\StrategyInterface.
 */

namespace Drupal\cron\Strategy;

use Cron\Job\AbstractProcessJob;
use Drupal\cron\Entity\CronJob;

/**
 * Interface StrategyInterface
 * @package Drupal\cron\Strategy
 */
interface StrategyInterface {

  /**
   * @return string
   */
  public function getType();

  /**
   * @param CronJob $db_job
   *
   * @return bool
   */
  public function handles(CronJob $db_job);

  /**
   * @param CronJob $db_job
   *
   * @return AbstractProcessJob
   */
  public function buildJob(CronJob $db_job);
}
