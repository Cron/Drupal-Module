<?php

/**
 * @file
 * Contains \Drupal\cron\Controller\CronController.
 */

namespace Drupal\cron\Controller;

use Drupal\cron\Command\CronRunCommand;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for cron routes.
 */
class CronController extends ControllerBase {

  /**
   * Prints a page listing the available cron jobs.
   *
   * @return string
   *   An HTML string representing the contents of cron main page.
   */
  public function cronMain() {

    $job = 'test';

    $command = new CronRunCommand();
    $command->execute($job);

    return '';
  }
}
