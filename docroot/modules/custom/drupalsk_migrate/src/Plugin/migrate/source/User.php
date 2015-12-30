<?php

/**
 * @file
 * Contains \Drupal\drupalsk_migrate\Plugin\migrate\source\User.
 */

namespace Drupal\drupalsk_migrate\Plugin\migrate\source;

use Drupal\migrate\Row;
use Drupal\user\Plugin\migrate\source\d6\User as OriginalUser;

/**
 * Extract users from Drupal 6 database.
 *
 * @MigrateSource(
 *   id = "dsk_user"
 * )
 */
class User extends OriginalUser {

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = $this->baseFields();
    $fields['dsk_name'] = $this->t('Name');
    $fields['dsk_surname'] = $this->t('Surname');
    $fields['dsk_web'] = $this->t('Web');
    $fields['dsk_job'] = $this->t('Job');
    $fields['dsk_twitter'] = $this->t('Twitter account');
    $fields['dsk_lat'] = $this->t('Latitude');
    $fields['dsk_long'] = $this->t('Longitude');

    // Add roles field.
    $fields['roles'] = $this->t('Roles');

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $uid = $row->getSourceProperty('uid');

    // User roles.
    $roles = $this->select('users_roles', 'ur')
      ->fields('ur', array('rid'))
      ->condition('ur.uid', $row->getSourceProperty('uid'))
      ->execute()
      ->fetchCol();
    $row->setSourceProperty('roles', $roles);

    // Name & surname.
    $result = $this->getDatabase()->query('
      SELECT
        prf.value
      FROM
        {profile_values} prf
      WHERE
        prf.uid = :uid
        AND prf.fid=1
    ', array(':uid' => $uid));
    foreach ($result as $record) {
      $names = explode(' ', $record->value, 2);
      $row->setSourceProperty('dsk_name', $names[0]);
      $row->setSourceProperty('dsk_surname', $names[1]);
    }

    // Web.
    $result = $this->getDatabase()->query('
      SELECT
        prf.value
      FROM
        {profile_values} prf
      WHERE
        prf.uid = :uid
        AND prf.fid=2
    ', array(':uid' => $uid));
    foreach ($result as $record) {
      $row->setSourceProperty('dsk_web', $record->value);
    }

    // Job.
    $result = $this->getDatabase()->query('
      SELECT
        prf.value
      FROM
        {profile_values} prf
      WHERE
        prf.uid = :uid
        AND prf.fid=5
    ', array(':uid' => $uid));
    foreach ($result as $record) {
      $row->setSourceProperty('dsk_job', $record->value);
    }

    // Twitter.
    $result = $this->getDatabase()->query('
      SELECT
        prf.value
      FROM
        {profile_values} prf
      WHERE
        prf.uid = :uid
        AND prf.fid=13
    ', array(':uid' => $uid));
    foreach ($result as $record) {
      $row->setSourceProperty('field_twitter', $record->value);
    }

    // Latitude & longitude.
    $result = $this->getDatabase()->query('
      SELECT
        loc.latitude,
        loc.longitude
      FROM
        {location} loc
      LEFT JOIN {location_instance} lic ON lic.lid=loc.lid
      WHERE
        lic.uid = :uid
    ', array(':uid' => $uid));
    foreach ($result as $record) {
      $row->setSourceProperty('dsk_lat', $record->latitude);
      $row->setSourceProperty('dsk_long', $record->longitude);
    }

    return parent::prepareRow($row);
  }
}
