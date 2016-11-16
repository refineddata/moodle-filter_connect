<?php // $Id: connectpro.php,v 1.00 2008/04/07 09:37:58 terryshane Exp $

require_once('../../config.php');
require_once( $CFG->dirroot . '/local/connect/lib.php' );
global $CFG, $DB, $PAGE, $USER;

$PAGE->set_url('/filter/connect/launch.php');

$acurl = optional_param('acurl', '', PARAM_RAW);
$courseid = optional_param('course', 1, PARAM_INT);
$archive = optional_param('archive', '', PARAM_ALPHANUM);
$guest = optional_param('guest', 0, PARAM_INT);
$edit = optional_param('edit', 0, PARAM_INT);
$type = optional_param('type', '', PARAM_ALPHA);
$forceaddin = optional_param('forceaddin', '0', PARAM_INT);
$cm = 0;
$context = context_course::instance($courseid);
$PAGE->set_context($context);

$url = str_replace('/', '', $acurl);
if ( !isset( $guest ) || !$guest ) {
	require_login();
}

//Check Locking
$courseid = isset($courseid) ? $courseid : 1;
$modules = array('connectmeeting', 'connectslide', 'connectquiz');

if ($course = $DB->get_record('course', array('id' => $courseid))) {
    foreach ( $modules as $module ){
        $connect = $DB->get_record($module, array('url' => $url, 'course' => $course->id), '*', IGNORE_MULTIPLE);
        if (empty($connect)) continue;
        
        if ($cm = get_coursemodule_from_instance($module, $connect->id, $course->id)) {
            if ( !isset( $guest ) || !$guest ) {
                require_course_login($course, false, $cm, true, false, true);
                // add them to group, just in case
                if( isset( $USER->id ) && $USER->id ){
                    connect_group_access( $USER->id, $course->id, true );
                }
            }
        }
        
    }
}

if (!$edit && !$archive && !$guest) {
    $func = $module . '_launch';   
    require_once( $CFG->dirroot . '/mod/'.$module.'/lib.php' );
    $func($acurl, $courseid, true, $cm);
}

if( $forceaddin ){
	$url.= $forceaddin == 1 ? '?launcher=true' : '?launcher=false';	
}

$launch_url = connect_get_launch_url($url, $type, $edit, $archive, $guest);
if (is_object($launch_url)){
    print_error($launch_url->error);
} else {
    header("Location: " . $launch_url);
}
exit(1);
