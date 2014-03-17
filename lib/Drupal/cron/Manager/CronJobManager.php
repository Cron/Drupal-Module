<?php

/**
 * @file
 * CronJob Manager to collect database interactions.
 */

namespace Drupal\cron\Manager;

/**
 * Class CronJobManager
 * @package Drupal\cron\Manager
 */
class CronJobManager {

  /**
   * Load cron job by id.
   *
   * @param int $id
   * @param bool $reset

   * @return \Drupal\cron\Entity\CronJob
   */
  public function loadSingle($id, $reset = FALSE) {
    return entity_load('cron_job', $id, $reset);
  }

  /**
   * Load multiple cron jobs at once.
   *
   * @param array $ids
   * @param bool $reset
   *
   * @return \Drupal\cron\Entity\CronJob[]
   */
  public function loadMultiple(array $ids = NULL, $reset = FALSE) {
    return entity_load_multiple('cron_job', $ids, $reset);
  }

  /**
   * Load cron job by name.
   *
   * @param string $name

   * @return bool|mixed
   */
  public function loadByName($name) {
    $users = entity_load_multiple_by_properties('cron_job', array('name' => $name));

    return $users ? reset($users) : FALSE;
  }

  /**
   * Load all enabled cron jobs.
   *
   * @return \Drupal\cron\Entity\CronJob[]
   */
  public function loadEnabledJobs() {
    $jobs = \Drupal::entityQuery('cron_job')
      ->condition('enabled', 1)
      ->sort('name', 'ASC')
      ->execute();

    return entity_load_multiple('cron_job', $jobs);
  }
}
