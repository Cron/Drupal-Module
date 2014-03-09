<?php

/**
 * @file
 * The CronJob entity class.
 */

namespace Drupal\cron\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\FieldDefinition;

/**
 * Defines the cronjob entity class.
 *
 * @ContentEntityType(
 *   id = "cron_job",
 *   label = @Translation("CronJob"),
 *   base_table = "cron_job",
 *   fieldable = FALSE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   }
 * )
 */
class CronJob extends ContentEntityBase {

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
   * @param string $name
   *
   * @return CronJob
   */
  public function setName($name) {
    $this->get('name')->value = $name;

    return $this;
  }

  /**
   * @return string
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * @param string $command
   */
  public function setCommand($command) {
    $this->get('command')->value = $command;
  }

  /**
   * @return string
   */
  public function getCommand() {
    return $this->get('command')->value;
  }

  /**
   * @param string $schedule
   */
  public function setSchedule($schedule) {
    $this->get('schedule')->value = $schedule;
  }

  /**
   * @return string
   */
  public function getSchedule() {
    return $this->get('schedule')->value;
  }

  /**
   * @param string $description
   *
   * @return CronJob
   */
  public function setDescription($description) {
    $this->get('description')->value = $description;

    return $this;
  }

  /**
   * @return string
   */
  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * @param boolean $enabled
   *
   * @return CronJob
   */
  public function setEnabled($enabled) {
    $this->get('enabled')->value = $enabled;

    return $this;
  }

  /**
   * @return boolean
   */
  public function getEnabled() {
    return $this->get('enabled')->value;
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
      ->setLabel(t('CronJob ID'))
      ->setDescription(t('The CronJob ID.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The CronJob UUID.'))
      ->setReadOnly(TRUE);

    //TODO: property constraints.
    $fields['name'] = FieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the CronJob.'))
      ->setSetting('default_value', '');

    //TODO: property constraints.
    $fields['command'] = FieldDefinition::create('string')
      ->setLabel(t('Command'))
      ->setDescription(t('The command the CronJob has to execute.'))
      ->setSetting('default_value', '');

    //TODO: property constraints.
    $fields['schedule'] = FieldDefinition::create('string')
      ->setLabel(t('Schedule'))
      ->setDescription(t('The schedule when the CronJob has to be executed.'))
      ->setSetting('default_value', '');

    //TODO: property constraints.
    $fields['description'] = FieldDefinition::create('string')
      ->setLabel(t('Description'))
      ->setDescription(t('The description of the CronJob.'))
      ->setSetting('default_value', '');

    $fields['enabled'] = FieldDefinition::create('boolean')
      ->setLabel(t('Enabled'))
      ->setDescription(t('Whether the CronJob is enabled (1) or disabled (0).'))
      ->setSetting('default_value', 1);

    return $fields;
  }
}
