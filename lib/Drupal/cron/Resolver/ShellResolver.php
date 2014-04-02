<?php

/**
 * @file
 * This file holds all functionality concerning the CronJob Resolver.
 */

namespace Drupal\cron\Resolver;

use Cron\Job\JobInterface;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Resolver\ResolverInterface;
use Cron\Schedule\CrontabSchedule;
use Drupal\cron\Entity\CronJob;

/**
 * Class ShellResolver
 * @package Drupal\cron\Resolver
 */
class ShellResolver implements ResolverInterface {
  /**
   * @var \Drupal\cron\Manager\CronJobManager
   */
  protected $manager;

  /**
   * @var \Drupal\cron\Entity\CronJob
   */
  protected $job = NULL;

  /**
   * @var bool
   */
  protected $force = FALSE;

  /**
   * Constructor.
   */
  public function __construct() {
    $this->manager = \Drupal::service('cron_job_manager');
  }

  /**
   * @param \Drupal\cron\Manager\CronJobManager $manager
   */
  public function setManager($manager) {
    $this->manager = $manager;
  }

  /**
   * @param \Drupal\cron\Entity\CronJob $job
   */
  public function setJob($job) {
    $this->job = $job;
  }

  /**
   * @return \Drupal\cron\Entity\CronJob
   */
  public function getJob() {
    return $this->job;
  }

  /**
   * @param boolean $force
   */
  public function setForce($force) {
    $this->force = $force;
  }

  /**
   * @return boolean
   */
  public function getForce() {
    return $this->force;
  }

  /**
   * Return all available jobs.
   *
   * @return JobInterface[]
   */
  public function resolve() {
    $jobs = array();

    if (!is_null($this->getJob()) && ($this->getJob()->getEnabled() || $this->getForce())) {
      $jobs[$this->getJob()->getId()] = $this->getJob();
    }
    else {
      $jobs = $this->manager->loadEnabledJobs();
    }

    return array_map(array($this, 'createJob'), $jobs);
  }

  /**
   * Transform a CronJob into a ShellJob.
   *
   * @param CronJob $db_job
   * @return ShellJob
   */
  protected function createJob(CronJob $db_job) {
    $job = new ShellJob();
    $job->setCommand($db_job->getCommand(), dirname(DRUPAL_ROOT));
    $job->setSchedule(new CrontabSchedule($db_job->getSchedule()));
    $job->raw = $db_job;

    return $job;
  }
}
