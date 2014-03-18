<?php

/**
 * @file
 * This file holds all functionality concerning the CronJob Resolver.
 */

namespace Drupal\cron\Resolver;

use Cron\Job\JobInterface;
use Cron\Job\ShellJob;
use Cron\Resolver\ResolverInterface;
use Cron\Schedule\CrontabSchedule;
use Drupal\cron\Entity\CronJob;
use Symfony\Component\Process\PhpExecutableFinder;

/**
 * Class CronJobResolver
 * @package Drupal\cron\Resolver
 */
class CronJobResolver implements ResolverInterface {
  /**
   * @var \Drupal\cron\Manager\CronJobManager
   */
  protected $manager;

  /**
   * @var string
   */
  protected $phpExecutable;

  public function __construct() {
    $finder = new PhpExecutableFinder();
    $this->phpExecutable = $finder->find();
  }

  /**
   * @param \Drupal\cron\Manager\CronJobManager $manager
   */
  public function setManager($manager) {
    $this->manager = $manager;
  }

  /**
   * Return all available jobs.
   *
   * @return JobInterface[]
   */
  public function resolve() {
    $jobs = $this->manager->loadEnabledJobs();

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
    $job->setCommand($this->phpExecutable . ' app/console ' . $db_job->getCommand(), dirname(DRUPAL_ROOT));
    $job->setSchedule(new CrontabSchedule($db_job->getSchedule()));
    $job->raw = $db_job;

    return $job;
  }
}
