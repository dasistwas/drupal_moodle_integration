(function($, Drupal, drupalSettings) {
      Drupal.behaviors.AudienceVisibilityBehavior = {
          attach: function(context, settings) {
           //move description below labels
            $("#moodle_enrol").click(function(event ) {
              event.preventDefault();
              var moodle_user_id = $(this).attr('moodle-user-id');
              var course_id = $(this).attr('course-id');
              alert(course_id);
              $.ajax({
                url: 'course/unenrol/'+moodle_user_id+'/'+course_id,
                type: 'POST',
                success: function (data) {
                  location.reload();
                }
              });
            });
          }
      };
  })(jQuery, Drupal, drupalSettings);