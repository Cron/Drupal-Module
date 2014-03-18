<?php

/**
 * @file
 * The CronReport entity class.
 */

namespace Drupal\cron\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\FieldDefinition;

/**
 * Defines the cronreport entity class.
 *
 * @ContentEntityType(
 *   id = "cron_report",
 *   label = @Translation("CronReport"),
 *   base_table = "cron_report",
 *   fieldable = FALSE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class CronReport extends ContentEntityBase {

  /**
   * @return integer
   */
  public function getId() {
    return $this->get('id')->value;
  }

  /**
   * @return integer
   */
  public function getUuid() {
    return $this->get('uuid')->value;
  }

  /**
   * @param int $run_at
   */
  public function setRunAt($run_at) {
    $this->get('run_at')->value = $run_at;
  }

  /**
   * @return int
   */
  public function getRunAt() {
    return $this->get('run_at')->value;
  }

  /**
   * @param int $run_time
   */
  public function setRunTime($run_time) {
    $this->get('run_time')->value = $run_time;  }

  /**
   * @return int
   */
  public function getRunTime() {
    return $this->get('run_time')->value;
  }

  /**
   * @param int $exit_code
   */
  public function setExitCode($exit_code) {
    $this->get('exit_code')->value = $exit_code;
  }

  /**
   * @return int
   */
  public function getExitCode() {
    return $this->get('exit_code')->value;
  }

  /**
   * @param string $output
   */
  public function setOutput($output) {
    $this->get('output')->value = $output;
  }

  /**
   * @return string
   */
  public function getOutput() {
    return $this->get('output')->value;
  }

  /**
   * @param CronJob $job
   */
  public function setJob($job) {
    $this->get('job')->value = $job->getId();
  }

  /**
   * @return CronJob
   */
  public function getJob() {
    return entity_load('cron_job', $this->get('job')->value);
  }

  /**
   * {@inheritdoc}
   */
  public function isNew() {
    return !empty($this->enforceIsNew) || $this->getId() === NULL;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions($entity_type) {
    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('CronReport ID'))
      ->setDescription(t('The CronReport ID.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The CronReport UUID.'))
      ->setReadOnly(TRUE);

    //TODO: property constraints.
    $fields['run_at'] = FieldDefinition::create('integer')
      ->setLabel(t('Run at'))
      ->setDescription(t('Datetime when the cron job was run.'));

    //TODO: property constraints.
    $fields['run_time'] = FieldDefinition::create('integer')
      ->setLabel(t('Run time'))
      ->setDescription(t('The amount of time the cron job has run.'));

    //TODO: property constraints.
    $fields['exit_code'] = FieldDefinition::create('integer')
      ->setLabel(t('Exit code'))
      ->setDescription(t('The exit code of the cron job.'));

    //TODO: property constraints.
    $fields['output'] = FieldDefinition::create('string')
      ->setLabel(t('Output'))
      ->setDescription(t('The output of the cron job.'))
      ->setSetting('default_value', '');

    //TODO: property constraints.
    $fields['job'] = FieldDefinition::create('integer')
      ->setLabel(t('Cron job'))
      ->setDescription(t('The cron job this report belongs to.'));

    return $fields;
  }
}
