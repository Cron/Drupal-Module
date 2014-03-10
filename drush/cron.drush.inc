<?php

/**
 * @file
 * Drush integration for the cron module.
 */

/**
 * Implements hook_drush_command().
 */
function cron_drush_command() {
  $items = array();

  $list_command = new Drupal\cron\Command\CronListCommand();
  $list_command->configure($items);

  $create_command = new Drupal\cron\Command\CronCreateCommand();
  $create_command->configure($items);

  $create_command = new Drupal\cron\Command\CronEnableCommand();
  $create_command->configure($items);

  return $items;
}

/**
 * Cron list command callback.
 */
function cron_drush_list() {
  $command = new Drupal\cron\Command\CronListCommand();
  $command->execute();
}

/**
 * Cron create command callback.
 */
function cron_drush_create() {
  $command = new Drupal\cron\Command\CronCreateCommand();
  $command->execute();
}

/**
 * Cron enable command callback.
 */
function cron_drush_enable($job) {
  $command = new Drupal\cron\Command\CronEnableCommand();
  $command->execute($job);
}
