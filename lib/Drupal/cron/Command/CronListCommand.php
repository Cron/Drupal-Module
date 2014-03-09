<?php

/**
 * @file
 * Drush list cron jobs functionality.
 */

namespace Drupal\cron\Command;

/**
 * Class CronListCommand
 * @package Drupal\cron\Command
 */
class CronListCommand {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:list'] = array(
      'description' => dt('List all available crons.'),
      'callback' => 'cron_drush_list',
    );
  }

  /**
   * Return list of cron jobs to CLI.
   */
  public function execute() {
    $jobs = $this->queryJobs();

    foreach ($jobs as $job) {
      $state = $job->getEnabled() ? 'x' : ' ';
      drush_print(dt('[!enabled] !name', array('!enabled' => $state, '!name' => $job->getName())), 1);
    }
  }

  /**
   * Get list of cron jobs ordered by name.
   */
  private function queryJobs() {
    $jobs = \Drupal::entityQuery('cron_job')
      ->sort('name', 'ASC')
      ->execute();

    return entity_load_multiple('cron_job', $jobs);
  }
}
