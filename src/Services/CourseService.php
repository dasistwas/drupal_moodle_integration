<?php

namespace Drupal\drupal_moodle_integration\Services;
use Drupal\moodle_integration\Utility;
use \Drupal\Core\Database\Connection;

/**
 * Class CustomService.
 */
class CourseService
{
  public function getCoursesList() {

    $config    = \Drupal::config('moodle.settings');
    $baseurl   = $config->get('url') . '/webservice/rest/server.php?';
  $params = array(
    'wstoken' => 'a5f4f1801d6268ad29b11ffcb51942d9',
    'wsfunction' => 'core_course_get_courses',
    'moodlewsrestformat' => 'json',
 );

 $url       = $baseurl . http_build_query($params);
 $response  = file_get_contents($url);
 $newusers  = json_decode($response);
 // print_r($newusers);die;
 return $newusers;
}
    public function userAssignedcourses() {
      $config    = \Drupal::config('moodle.settings');
      $baseurl   = $config->get('url') . '/webservice/rest/server.php?';
      $user      = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
      $moodle_id = $user->field_moodle_user_id->value;
      $params    = array(
          'wstoken' => $config->get('wstoken'),
          'wsfunction' => 'core_enrol_get_users_courses',
          'moodlewsrestformat' => 'json',
          'userid' => $moodle_id
      );
      $url       = $baseurl . http_build_query($params);
      $response  = file_get_contents($url);
      $newusers  = json_decode($response);
      // print_r($newusers);die;
      return $newusers;
    }
    /**
     * Here you can pass your values as $array.
     */
    public function getActivities($courseid) {
      $config    = \Drupal::config('moodle.settings');
      $baseurl   = $config->get('url') . '/webservice/rest/server.php?';
      $params = array(
        'wstoken' =>  $config->get('wstoken'),
        'wsfunction' => 'core_course_get_contents',
        'moodlewsrestformat' => 'json',
      );
      $params['courseid']=$courseid;
      $url = $baseurl . http_build_query($params);
      $response = file_get_contents($url);
      $newusers = json_decode($response);
      return $newusers;
    }

    function courseEnrol($users) {
      $config =  \Drupal::config('moodle.settings');
      $baseurl = $config->get('url').'/webservice/rest/server.php?';
      $params = array(
        'wstoken' => 'a5f4f1801d6268ad29b11ffcb51942d9',
        'wsfunction' => 'enrol_manual_enrol_users',
        'moodlewsrestformat' => 'json',
      );
      $params['enrolments'][0]['roleid']= 5;
      $params['enrolments'][0]['userid']= 20;
      $params['enrolments'][0]['courseid']= 2;
      $url = $baseurl . http_build_query($params);
      $response = file_get_contents($url);
      $newusers = json_decode($response);
    }

    function courseUnEnrol($userid,$courseid) {
      $config =  \Drupal::config('moodle.settings');
      $baseurl = $config->get('url').'/webservice/rest/server.php?';
      $params = array(
        'wstoken' => 'a5f4f1801d6268ad29b11ffcb51942d9',
        'wsfunction' => 'enrol_manual_unenrol_users',
        'moodlewsrestformat' => 'json',
      );
      $params['enrolments'][0]['roleid']= 5;
      $params['enrolments'][0]['userid']= $userid;
      $params['enrolments'][0]['courseid']= $courseid;
      $url = $baseurl . http_build_query($params);
      $response = file_get_contents($url);
      $newusers = json_decode($response);
    }

}
