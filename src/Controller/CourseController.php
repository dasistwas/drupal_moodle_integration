<?php

namespace Drupal\drupal_moodle_integration\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;

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
  public function coursesList() {
    global $base_url;
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    $user = User::load(\Drupal::currentUser()->id());
    $moodle_id = $user->field_moodle_user_id->value;
    return [
      '#theme' => 'moodle_course_list',
      '#course_list' => $service->getCoursesList(),
      '#moodle_user_id' => $moodle_id,
      '#attached' => [
        'library' => [
          'drupal_moodle_integration/drupal_moodle_integration',
        ],
      ],
    ];
  }

  /**
   * Functoin to enroll course.
   */
  public function userEnrolledCourse() {
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    $user = User::load(\Drupal::currentUser()->id());
    $moodle_id = $user->field_moodle_user_id->value;
    return [
      '#theme' => 'moodle_course',
      '#course' => $service->userAssignedcourses(),
      '#moodle_user_id' => $moodle_id,
      '#attached' => [
        'library' => [
          'drupal_moodle_integration/drupal_moodle_integration',
        ],
      ],
    ];
  }

  /**
   * Function to get list of activities form moodle.
   */
  public function courseGetActivities() {
    $path = \Drupal::request()->getpathInfo();
    $arg = explode('/', $path);
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    return [
      '#theme' => 'moodle_course_activity',
      '#course_activity' => $service->getActivities($arg[4]),
    ];
  }

  /**
   * Function to Unenrol.
   */
  public function courseUnenrol() {
    $path = \Drupal::request()->getpathInfo();
    $arg = explode('/', $path);
    $service = \Drupal::service('drupal_moodle_integration.course_services');
    $service->courseUnEnrol($arg[4], $arg[5]);
  }

}
