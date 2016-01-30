# Drupal.sk community site

[![Join the chat at https://gitter.im/slovak-drupal-association/komunita.drupal.sk](https://badges.gitter.im/slovak-drupal-association/komunita.drupal.sk.svg)](https://gitter.im/slovak-drupal-association/komunita.drupal.sk?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

## Priorities for Spring 2016 sprint
* [x] #2 - update core
* Blogs
 - [x] #5 - create content type
 - [x] #11 - display blogs
 - [ ] #12 - theme blogs
 - [ ] #10 - migrate content from old site
* Forum
 - [ ] #3 - enable module
 - [ ] #6 - create forum blocks
 - [ ] #7 - theme forum
 - [ ] #4 - migrate old content
* Other
 - [ ] #9 - theme showcases
 - [ ] #8 - enhacements in showcases

## Contents
* config/ - Exported configuration.
* docroot/ - Website directory.

## Requirements
* Install composer: https://getcomposer.org/doc/00-intro.md
* Install Drush version 8: http://docs.drush.org/en/master/install/
* Drupal 8 requirements: https://www.drupal.org/requirements

## Getting the site up and running.
* Get your copy of the code:
  * Fork this repository. ( https://help.github.com/articles/fork-a-repo/ )
  * Clone your repository.
  * `git clone git@github.com:[YOUR-NAME]/komunita.drupal.sk.git komunita.drupal.sk`
  * `cd komunita.drupal.sk`
* Prepare your database and fill the credentials into your new local config.
  * `cp docroot/sites/default.settings.local.php docroot/sites/settings.local.php`
  * edit this config: `docroot/sites/settings.local.php`
  * you need set `$settings['hash_salt']` to some random string value. This value is used for hashing passwords on your site.
  * (optional) set DB connection string. Some stacks might generate this for you. Look for `$databases` non-empty array. 
* Install the site using Drupal.sk profile (distribution).
  * `drush si -y`
* After you finish working, export configuration
  * `drush cex sync -y`
  
## Contributing
* We are using GitFlow(https://www.atlassian.com/git/tutorials/comparing-workflows/gitflow-workflow/) branching strategy
  * you need to create ```feature/NAME``` branch for each issue
  * after you finish work on issue, create pull request against ```develop``` branch 
* Commit your changes. ( http://chris.beams.io/posts/git-commit/ )
* Create pull request. https://help.github.com/articles/creating-a-pull-request/
