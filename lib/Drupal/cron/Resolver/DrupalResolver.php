<?php

/**
 * @file
 * This file holds all functionality concerning the Drupal resolver.
 */

namespace Drupal\cron\Resolver;

use Cron\Job\AbstractProcessJob;
use Cron\Job\JobInterface;
use Cron\Resolver\ResolverInterface;
use Drupal\cron\Entity\CronJob;
use Drupal\cron\Strategy\StrategyInterface;

/**
 * Class DrupalResolver
 * @package Drupal\cron\Resolver
 */
class DrupalResolver implements ResolverInterface {

  /**
   * @var StrategyInterface[]
   */
  private $strategies;

  /**
   * @var CronJob[]
   */
  private $jobs;

  /**
   * @param StrategyInterface $strategy
   */
  public function addStrategy(StrategyInterface $strategy) {
    $this->strategies[] = $strategy;
  }

  /**
   * @param CronJob[] $jobs
   */
  public function setJobs($jobs) {
    $this->jobs = $jobs;
  }

  /**
   * @return CronJob[]
   */
  public function getJobs() {
    return $this->jobs;
  }

  /**
   * Build a job for the CronJobs.
   *
   * @return JobInterface[]
   */
  public function resolve() {
    return array_map(array($this, 'buildJob'), $this->jobs);
  }

  /**
   * Allow the handling strategy to build the job.
   *
   * @param CronJob $db_job
   *
   * @return AbstractProcessJob
   */
  protected function buildJob(CronJob $db_job) {
    foreach ($this->strategies as $strategy) {
      if ($strategy->handles($db_job)) {
        return $strategy->buildJob($db_job);
      }
    }

    return FALSE;
  }
}
