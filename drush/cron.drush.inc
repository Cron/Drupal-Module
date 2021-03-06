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

   //These includes are needed for drush cache-rebuild support since there are no classes loaded at that point.
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronCommandBase.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronListCommand.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronCreateCommand.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronEnableCommand.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronDisableCommand.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronDeleteCommand.php';
  require_once dirname(__FILE__) . '/../lib/Drupal/cron/Command/CronRunCommand.php';

  $list_command = new Drupal\cron\Command\CronListCommand();
  $list_command->configure($items);

  $create_command = new Drupal\cron\Command\CronCreateCommand();
  $create_command->configure($items);

  $enable_command = new Drupal\cron\Command\CronEnableCommand();
  $enable_command->configure($items);

  $disable_command = new Drupal\cron\Command\CronDisableCommand();
  $disable_command->configure($items);

  $delete_command = new Drupal\cron\Command\CronDeleteCommand();
  $delete_command->configure($items);

  $run_command = new Drupal\cron\Command\CronRunCommand();
  $run_command->configure($items);

  return $items;
}

/**
 * Cron list command callback.
 */
function cron_drush_list() {
  \Drupal::service('cron_list_command')->execute();
}

/**
 * Cron create command callback.
 */
function cron_drush_create() {
  \Drupal::service('cron_create_command')->execute();
}

/**
 * Cron enable command callback.
 */
function cron_drush_enable($job) {
  \Drupal::service('cron_enable_command')->execute($job);
}

/**
 * Cron disable command callback.
 */
function cron_drush_disable($job) {
  \Drupal::service('cron_disable_command')->execute($job);
}

/**
 * Cron delete command callback.
 */
function cron_drush_delete($job) {
  \Drupal::service('cron_delete_command')->execute($job);
}

/**
 * Cron run command callback.
 */
function cron_drush_run($job = NULL) {
  $command = new Drupal\cron\Command\CronRunCommand();
  $command->execute($job);
}
