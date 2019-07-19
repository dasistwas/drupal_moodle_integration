<?php

namespace Drupal\drupal_moodle_integration\Services;

use GuzzleHttp\Client;

/**
 * Class MoodleService.
 */
class MoodleService {

  /**
   * @var \Drupal\Core\Config\ImmutableConfig|null
   */
  protected $config = NULL;

  /**
   * @var string
   */
  protected $baseurl = '';

  /**
   * @var Client
   */
  protected $client;

  /**
   * @var string
   */
  protected $method = 'POST';

  /**
   * Service Constructor.
   */
  public function __construct() {
    $this->config = \Drupal::config('moodle.settings');
    $this->baseurl = $this->config->get('url') . '/webservice/rest/server.php';
    $this->basequery = [
      'wstoken' => $this->config->get('wstoken'),
      'moodlewsrestformat' => $this->config->get('moodlewsrestformat'),
    ];
    $this->client = new Client(['base_uri' => $this->baseurl]);
  }

  /**
   * Call the Moodle web service.
   *
   * @param string $wsfunction
   * @param array $moodleparams
   *
   * @return mixed|\Psr\Http\Message\ResponseInterface
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function callEndpoint($wsfunction, $moodleparams = []) {
    $params = [];
    $params['form_params'] = $moodleparams;
    $wsf = ['wsfunction' => $wsfunction];
    $basequery = array_merge($wsf, $this->basequery);
    $params['query'] = $basequery;
    $response = $this->client->post('', $params);
    return $this->responseValidation($response);
  }

  /**
   * Analyzes the response and returns an array with ['error'] and ['data']
   * if error is empty string, data is set. When an error occurs data is empty
   * and error contains the error info string.
   *
   * @param \GuzzleHttp\Psr7\Response $response
   *
   * @return array
   */
  public function responseValidation(\GuzzleHttp\Psr7\Response $response) {
    $return = [];
    $return['error'] = '';
    $return['data'] = NULL;
    if ($response->getStatusCode() >= 400) {
      $return['error'] = 'Server error';
    }
    else {
      $fetcheddata = json_decode($response->getBody());
      if (is_object($fetcheddata) && !empty(($fetcheddata->exception))) {
        $return['error'] = 'Error: ' . $fetcheddata->exception . ' Code: ' . $fetcheddata->errorcode .
          ' Message: ' . $fetcheddata->message;
        if (isset($fetcheddata->debuginfo)) {
          $return['error'] .= ' Debuginfo: ' . $fetcheddata->debuginfo;
        }
      }
      else {
        $return['data'] = $fetcheddata;
      }
    }
    return $return;
  }

  /**
   * @param array $params params[ids][0]= int
   *
   * @return mixed|\Psr\Http\Message\ResponseInterface
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function getCoursesList($params) {
    return $this->callEndpoint('core_course_get_courses', $params);
  }

  /**
   * @param array $params
   *
   * Example: $courses['courses'][0] = array('fullname' => 'fullname');
   * courses[0][fullname]= string
   * courses[0][shortname]= string
   * courses[0][categoryid]= int
   * courses[0][idnumber]= string
   * courses[0][summary]= string
   * courses[0][summaryformat]= int
   * courses[0][format]= string
   * courses[0][showgrades]= int
   * courses[0][newsitems]= int
   * courses[0][startdate]= int
   * courses[0][enddate]= int
   * courses[0][numsections]= int
   * courses[0][maxbytes]= int
   * courses[0][showreports]= int
   * courses[0][visible]= int
   * courses[0][hiddensections]= int
   * courses[0][groupmode]= int
   * courses[0][groupmodeforce]= int
   * courses[0][defaultgroupingid]= int
   * courses[0][enablecompletion]= int
   * courses[0][completionnotify]= int
   * courses[0][lang]= string
   * courses[0][forcetheme]= string
   * courses[0][courseformatoptions][0][name]= string
   * courses[0][courseformatoptions][0][value]= string
   *
   * @return object (id, shortname)
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function createCourse($params) {
    return $this->callEndpoint('core_course_create_courses', $params);
  }

  /**
   * params[courseid] = int (course to duplicate id)
   * params[fullname] = string
   * params[shortname] = string
   * params[categoryid] = int
   *
   * Further options:
   * params['options'][0][name]= string
   * params['options'][0][value]= string
   *
   * Example option
   * params['options'][0][name]= 'activities'
   * params['options'][0][value]= 1
   *
   *
   * @return object (id, shortname)
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function duplicateCourse($params) {
    return $this->callEndpoint('core_course_duplicate_course', $params);
  }

  /**
   * params['courses'][0][id]= int
   * params['courses'][0][fullname]= string
   * params['courses'][0][shortname]= string
   * params['courses'][0][categoryid]= int
   * params['courses'][0][idnumber]= string
   * params['courses'][0][summary]= string
   * params['courses'][0][summaryformat]= int
   * params['courses'][0][format]= string
   * params['courses'][0][showgrades]= int
   * params['courses'][0][newsitems]= int
   * params['courses'][0][startdate]= int
   * params['courses'][0][enddate]= int
   * params['courses'][0][numsections]= int
   * params['courses'][0][maxbytes]= int
   * params['courses'][0][showreports]= int
   * params['courses'][0][visible]= int
   * params['courses'][0][hiddensections]= int
   * params['courses'][0][groupmode]= int
   * params['courses'][0][groupmodeforce]= int
   * params['courses'][0][defaultgroupingid]= int
   * params['courses'][0][enablecompletion]= int
   * params['courses'][0][completionnotify]= int
   * params['courses'][0][lang]= string
   * params['courses'][0][forcetheme]= string
   * params['courses'][0][courseformatoptions][0][name]= string
   * params['courses'][0][courseformatoptions][0][value]= string
   *
   *
   * @return data
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function updateCourse($params) {
    return $this->callEndpoint('core_course_update_courses', $params);
  }

  /**
   * User Assigned Courses.
   */
  public function userAssignedcourses($params) {
    return $this->callEndpoint('core_enrol_get_users_courses', $params);
  }

  /**
   * params['enrolments'][0]['roleid'] = ...
   * roleid int   //Role to assign to the user
   * userid int   //The user that is going to be enrolled
   * courseid int   //The course to enrol the user role in
   * timestart int  Optional //Timestamp when the enrolment start
   * timeend int  Optional //Timestamp when the enrolment end
   * suspend int  Optional //set to 1 to suspend the enrolment
   *
   */
  public function enrolUserToCourse($params) {
    return $this->callEndpoint('enrol_manual_enrol_users', $params);
  }

  /**
   * Course Unenrol.
   * userid int   //The user that is going to be unenrolled
   * courseid int   //The course to unenrol the user from
   * roleid int  Optional //The user role
   */
  public function unEnrolFromCourse($params) {
    return $this->callEndpoint('enrol_manual_unenrol_users', $params);
  }

  /**
   * Function to get Create users in moodle.
   *
   * username string   //Username policy is defined in Moodle security config.
   * password string  Optional //Plain text password consisting of any characters
   * createpassword int  Optional //True if password should be created and mailed to user.
   * firstname string   //The first name(s) of the user
   * lastname string   //The family name of the user
   * email string   //A valid and unique email address
   * auth string  Default to "manual" //Auth plugins include manual, ldap, etc
   * idnumber string  Default to "" //An arbitrary ID code number perhaps from the institution
   * lang string  Default to "en" //Language code such as "en", must exist on server
   * calendartype string  Default to "gregorian" //Calendar type such as "gregorian", must exist on server
   * theme string  Optional //Theme name such as "standard", must exist on server
   * timezone string  Optional //Timezone code such as Australia/Perth, or 99 for default
   * mailformat int  Optional //Mail format code is 0 for plain text, 1 for HTML etc
   * description string  Optional //User profile description, no HTML
   * city string  Optional //Home city of the user
   * country string  Optional //Home country code of the user, such as AU or CZ
   * firstnamephonetic string  Optional //The first name(s) phonetically of the user
   * lastnamephonetic string  Optional //The family name phonetically of the user
   * middlename string  Optional //The middle name of the user
   * alternatename string  Optional //The alternate name of the user
   * preferences  Optional //User preferences
   */
  public function moodleCreateUser($params) {
    return $this->callEndpoint('core_user_create_users', $params);
  }

  /**
   * Function to delete users in moodle.
   * @param array $params userid int
   */
  public function moodleDeleteUser($params) {
    return $this->callEndpoint('core_user_delete_users', $params);
  }

  /**
   * Function to update users in moodle.
   */
  public function moodleUpdateUser($params) {
    return $this->callEndpoint('core_user_update_users', $params);
  }

  /**
   * Function to update users in moodle.
   */
  public function getCourseCompletionStatus($params) {
    return $this->callEndpoint('core_completion_get_course_completion_status', $params);
  }
}