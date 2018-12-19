<?php

namespace Drupal\drupal_moodle_integration\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\drupal_moodle_integration\Utility;

/**
 * Defines CourseController class.
 */
class CourseController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    $user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
    $moodle_id= $user->field_moodle_user_id->value;
    return [
      '#theme' => 'moodle_course',
      '#course' =>  $service->getServiceData(),
      '#moodle_user_id' => $moodle_id,
      '#attached' => array(
        'library' => array(
          'drupal_moodle_integration/drupal_moodle_integration',
        )
      )
    ];

  }

  public function courseGetActivities() {
    $path = \Drupal::request()->getpathInfo();
    $arg  = explode('/',$path);
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    return [
       	'#theme' => 'moodle_course_activity',
        '#course_activity' => $service->getActivities($arg[4]),
    ];

  }

  public function courseUnenrol() {
    $path = \Drupal::request()->getpathInfo();
    $arg  = explode('/',$path);
   // print_r($arg);die;
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    $service->courseUnEnrol($arg[4],$arg[5]);
  }

}
