<?php

/**
 * @file
 * Cron command base class.
 */

namespace Drupal\cron\Command;

/**
 * Class CronCommandBase
 * @package Drupal\cron\Command
 */
class CronCommandBase {

  /**
   * Returns the service container.
   *
   * @return \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   */
  protected function container() {
    //TODO: figure out what to use instead.
    return \Drupal::getContainer();
  }
}
