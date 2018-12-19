<?php

namespace Drupal\drupal_moodle_integration\Services;

/**
 * Class UserService.
 */

class UserService {

  function moodleCreateUser($users) {
    $config =  \Drupal::config('moodle.settings');
    $baseurl = $config->get('url').'/webservice/rest/server.php?';
    $params = array(
      'wstoken' => $config->get('wstoken'),
      'wsfunction' => 'core_user_create_users',
      'moodlewsrestformat' => 'json',
      'users' => $users,
    );
    $url = $baseurl . http_build_query($params);
    $response = file_get_contents($url);
    $newusers = json_decode($response);
    return $newusers[0]->id;
  }

  function moodleUpdateUser($users) {
    $config =  \Drupal::config('moodle.settings');
    $baseurl = $config->get('url').'/webservice/rest/server.php?';
    $params = array(
      'wstoken' => $config->get('wstoken'),
      'wsfunction' => 'core_user_update_users',
      'moodlewsrestformat' => 'json',
      'users' => $users,
    );
    $url = $baseurl . http_build_query($params);
    $response = file_get_contents($url);
    $newusers = json_decode($response);
  }

}


