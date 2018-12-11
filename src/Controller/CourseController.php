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
    $service->getServiceData();
        return [
        	'#theme' => 'moodle_course',
          '#course' =>  $service->getServiceData(),
        ];
  }

  public function courseActivities() {

  }

}
