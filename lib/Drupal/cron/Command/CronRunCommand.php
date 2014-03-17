<?php

/**
 * @file
 * Drush run cron job functionality.
 */

namespace Drupal\cron\Command;

use Cron\Cron;
use Cron\Executor\Executor;
use Cron\Job\ShellJob;
use Cron\Resolver\ArrayResolver;
use Cron\Schedule\CrontabSchedule;
use Symfony\Component\Process\PhpExecutableFinder;

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
    $force = drush_get_option('force', FALSE);

    $cron = new Cron();
    $cron->setExecutor(new Executor());

    if (is_null($job)) {
      $resolver = $this->container()->get('cron_job_resolver');
    }
    else {
      $resolver = $this->getJobResolver($job, $force);
    }

    $cron->setResolver($resolver);

    $time = microtime(true);
    $report = $cron->run();

    while ($cron->isRunning()) {}

    drush_log(dt('time: !time', array('!time' => microtime(true) - $time)));
  }

  /**
   * Get cron job resolver.
   *
   * @param string $job_name
   * @param bool $force
   *
   * @return ArrayResolver
   */
  public function getJobResolver($job_name, $force = FALSE) {
    $db_job = $this->queryJob($job_name);

    if (!$db_job) {
      drush_set_error('cron', dt('The specified job does not exist.'));
    }

    $finder = new PhpExecutableFinder();
    $php_executable = $finder->find();
    $resolver = new ArrayResolver();

    if ($db_job->getEnabled() || $force) {
      $job = new ShellJob();
      $job->setCommand($php_executable . ' app/console ' . $db_job->getCommand(), dirname(DRUPAL_ROOT));
      $job->setSchedule(new CrontabSchedule($db_job->getSchedule()));
      $job->raw = $db_job;

      $resolver->addJob($job);
    }

    return $resolver;
  }

  /**
   * Get CronJob entity from name.
   *
   * @param string $name
   *
   * @return \Drupal\cron\Entity\CronJob
   */
  protected function queryJob($name) {
    return $this->container()->get('cron_job_manager')
      ->loadByName($name);
  }
}
