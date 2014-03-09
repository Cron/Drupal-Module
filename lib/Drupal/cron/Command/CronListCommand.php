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

    while ($job = $jobs->fetchAssoc()) {
      $state = $job['enabled'] ? 'x' : ' ';
      drush_print(dt('[!enabled] !name', array('!enabled' => $state, '!name' => $job['name'])), 1);
    }
  }

  /**
   * Get list of cron jobs ordered by name.
   */
  private function queryJobs() {
    $jobs = db_select('cron_job','cj')
      ->fields('cj')
      ->orderBy('cj.name', 'ASC')
      ->execute();

    return $jobs;
  }
}
