Drupal Cron Module
==================

__Cron integration for Drupal. At the moment only available for Drupal 8.__

This module is still in early development phase. __It can not (and should not) be used in production environments!__

This module will allow you to add, remove, enable, disable and run cron jobs on your Drupal 8 website using Drush.
This way you can add one single cron job to the crontab of your server which will trigger the configured cron jobs
on your Drupal site when needed.

Installation
------------

1. Make sure [Drush](https://github.com/drush-ops/drush) is installed (for Drupal 8)

2. Place this module in the desired module folder

1. Add the cron library dependency to the composer.json file of your Drupal installation
  ```javascript
  // composer.json
  {
      // ...
      require: {
          // ...
          "cron/cron": "1.0.*"
      }
  }
  ```

2. Update your composer installation
  ```shell
  composer update
  ````

5. Start using the bundle
  ```shell
  drush cron:list
  drush cron:run
  ```

Available commands
------------------

### list
```shell
drush cron:list
```
Show a list of all jobs. Job names are shown with ```[x]``` if they are enabled and ```[ ]``` otherwise.

### create
```shell
drush cron:create
```
Create a new job.

### delete
```shell
drush cron:delete [job]
```
Delete a job. For your own protection, the job must be disabled first.

### enable
```shell
drush cron:enable [job]
```
Enable a job.

### disable
```shell
drush cron:disable [job]
```
Disable a job.

### run
```shell
drush cron:run [--force] [job]
```
Run the cron.
If a job is given only this will be triggered.
You can trigger a specific job that is disabled by using _--force_.

Contributing
------------

> All code contributions - including those of people having commit access - must
> go through a pull request and approved by a core developer before being
> merged. This is to ensure proper review of all the code.
>
> Fork the project, create a feature branch, and send us a pull request.
>
> To ensure a consistent code base, you should make sure the code follows
> the [Drupal Coding Standards](https://drupal.org/coding-standards).

If you would like to help, take a look at the [list of issues](https://github.com/Cron/Drupal-Module/issues).

Requirements
------------

PHP 5.4 or above

Author and contributors
-----------------------

Joeri van Dooren

See also the list of [contributors](https://github.com/Cron/Drupal-Module/contributors) who participated in this project.

License
-------

This module is licensed under the MIT license.
