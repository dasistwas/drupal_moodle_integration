<?php

namespace Drupal\drupal_moodle_integration\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Controller\ControllerBase;
use Drupal\drupal_moodle_integration\Services\MoodleService;

/**
 * Defines CourseController class.
 */
class CourseController extends ControllerBase {

  /**
   * @var MoodleService
   */
  protected $service;

  public function __construct() {
    $this->service = new MoodleService();
  }

  /**
   * @return mixed|\Psr\Http\Message\ResponseInterface
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function createCourse() {
      $course = $this->service->createCourse($courseparams);
      return $course;
  }

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function coursesList() {
    $user = User::load(\Drupal::currentUser()->id());
    $moodle_id = $user->field_moodle_user_id->value;
    $courses = $this->service->getCoursesList([]);
    return [
      '#theme' => 'moodle_course_list',
      '#course_list' => $courses,
      '#moodle_user_id' => $moodle_id,
      '#attached' => [
        'library' => [
          'drupal_moodle_integration/drupal_moodle_integration',
        ],
      ],
    ];
  }

  /**
   * Function to enrol in course.
   */
  public function userEnrolledCourse() {
    $user = User::load(\Drupal::currentUser()->id());
    return [
      '#theme' => 'moodle_course',
      '#course' => $this->service->userAssignedcourses(['userid' => 2])
        ->getBody(),
      '#moodle_user_id' => $user->get('uid'),
      '#attached' => [
        'library' => [
          'drupal_moodle_integration/drupal_moodle_integration',
        ],
      ],
    ];
  }

  /**
   * Function to Unenrol.
   */
  public function courseUnenrol() {
    $path = \Drupal::request()->getpathInfo();
    $arg = explode('/', $path);
    $this->service->courseUnEnrol($arg[4], $arg[5]);
  }

}
