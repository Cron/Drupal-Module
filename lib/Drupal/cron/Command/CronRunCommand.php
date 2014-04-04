<?php

/**
 * @file
 * Drush run cron job functionality.
 */

namespace Drupal\cron\Command;

use Cron\Cron;
use Cron\Executor\Executor;
use Drupal\cron\Resolver\DrupalResolver;
use Drupal\cron\Strategy\ShellStrategy;

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
   * Run the cron job.
   */
  public function execute($job = NULL) {
    $force = drush_get_option('force', FALSE);

    $cron = new Cron();
    $cron->setExecutor(new Executor());

    $jobs = $this->loadJobs($job, $force);

    $resolver = \Drupal::service('cron_drupal_resolver');
    $resolver->addStrategy(new ShellStrategy());
    $resolver->setJobs($jobs);
    $cron->setResolver($resolver);

    $time = microtime(true);
    $report = $cron->run();

    while ($cron->isRunning()) {}

    drush_log(dt('time: !time', array('!time' => microtime(true) - $time)), 'ok');
  }

  /**
   * Load jobs from database.
   *
   * If a single job is provided, this job is loaded from the database,
   * otherwise all the jobs will be loaded.
   *
   * @param string $job
   * @param bool $force
   *
   * @return \Drupal\cron\Entity\CronJob[]
   */
  public function loadJobs($job, $force) {
    if (!is_null($job)) {
      $db_job = \Drupal::service('cron_job_manager')->loadByName($job);
      if ($db_job && ($db_job->getEnabled() || $force)) {
        return array($db_job->getId() => $db_job);
      }
    }
    else {
      return \Drupal::service('cron_job_manager')->loadEnabledJobs();
    }

    return array();
  }
}
