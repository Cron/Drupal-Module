<?php

/**
 * @file
 * Drush create cron job functionality.
 */

namespace Drupal\cron\Command;

/**
 * Class CronCreateCommand
 * @package Drupal\cron\Command
 */
class CronCreateCommand extends CronCommandBase {

  /**
   * Expose command properties to drush.
   *
   * @param array $items
   */
  public function configure(&$items) {
    $items['cron:create'] = array(
      'description' => dt('Create a cron job.'),
      'callback' => 'cron_drush_create',
    );
  }

  /**
   * Ask the cron job properties and persist job to database.
   */
  public function execute() {
    $job = array();

    drush_print('');
    drush_print(dt('The unique name how the job will be referenced.'));
    $name = drush_prompt(dt('Name'));
    while (!$this->validateJobName($name)) {
      drush_print('');
      drush_set_error('cron', dt('The name you chose was invalid, please try another one.'));
      $name = drush_prompt(dt('Name'));
    }
    $job['name'] = $name;
    drush_log('', 'ok');

    drush_print(dt('The type of the job (can only be set to "shell" at this time.'));
    $type = drush_prompt(dt('Type'));
    while (!$this->validateType($type)) {
      drush_print('');
      drush_set_error('cron', dt('The type you chose was invalid, please try another one.'));
      $type = drush_prompt(dt('Type'));
    }
    $job['type'] = $type;
    drush_log('', 'ok');

    drush_print(dt('The command to execute. You may add extra arguments.'));
    $command = drush_prompt(dt('Command'));
    while (!$this->validateCommand($command)) {
      drush_print('');
      drush_set_error('cron', dt('The command you chose was invalid, please try another one.'));
      $command = drush_prompt(dt('Command'));
    }
    $job['command'] = $command;
    drush_log('', 'ok');

    drush_print(dt('The schedule in the crontab syntax.'));
    $schedule = drush_prompt(dt('Schedule'));
    while (!$this->validateSchedule($schedule)) {
      drush_print('');
      drush_set_error('cron', dt('The schedule you chose was invalid, please try another one.'));
      $schedule = drush_prompt(dt('Schedule'));
    }
    $job['schedule'] = $schedule;
    drush_log('', 'ok');

    drush_print(dt('Some more information about the job.'));
    $job['description'] = drush_prompt(dt('Description'));
    drush_log('', 'ok');

    $job['enabled'] = drush_confirm(dt('Should the cron be enabled?'));

    drush_print('');
    if ($job_entity = entity_create('cron_job', $job)) {
      $job_entity->save();
      drush_log(dt('Cron "!name" was created successfully!', array('!name' => $job['name'])), 'ok');
    }
    else {
      drush_set_error('cron', dt('Something went wrong, please contact the website administrator.'));
    }
  }

  /**
   * Validate the job name.
   *
   * @param string $name
   *
   * @return bool
   */
  protected function validateJobName($name) {
    if (!$name || strlen($name) == 0) {
      return FALSE;
    }

    if ($this->queryJobExists($name)) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Validate the type.
   *
   * @param string $type
   *
   * @return bool
   */
  protected function validateType($type) {
    switch ($type) {
      case 'shell':
        return TRUE;
    }

    return FALSE;
  }

  /**
   * Validate the command.
   *
   * @param string $command
   *
   * @return bool
   */
  protected function validateCommand($command) {
    //TODO: validate command.

    return TRUE;
  }

  /**
   * Validate the schedule.
   *
   * @param string $schedule

   * @return bool
   */
  protected function validateSchedule($schedule) {
    //TODO: validate schedule.

    return TRUE;
  }

  /**
   * Check if a job with this name already exists.
   *
   * @param string $name
   *
   * @return \Drupal\cron\Entity\CronJob
   */
  protected function queryJobExists($name) {
    return \Drupal::service('cron_job_manager')
      ->loadByName($name);
  }
}
