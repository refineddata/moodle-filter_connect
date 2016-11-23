<?php
/**
 * connect_callback.php.
 *
 * @author     Dmitriy
 * @since      11/07/14
 */

define('AJAX_SCRIPT', true);
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/mod/connectmeeting/connectlib.php');
require_once($CFG->dirroot . '/filter/connect/lib.php');
require_once($CFG->dirroot . '/filter/connect/filter.php');

// This should be accessed by only valid logged in user.
if (!isloggedin() or isguestuser()) {
    die('Invalid access.');
}

// Start capturing output in case of broken plugins.
ajax_capture_output();

$acurl = required_param('acurl', PARAM_ALPHANUMEXT);
$sco = optional_param('sco', null, PARAM_ALPHANUMEXT);
$sco = json_decode($sco);
$courseid = optional_param('courseid', null, PARAM_ALPHANUMEXT);
$options = optional_param('options', null, PARAM_NOTAGS);
$frommymeetings = optional_param('frommymeetings', null, PARAM_INT);
$frommyrecordings = optional_param('frommyrecordings', null, PARAM_INT);

$element = explode("~", urldecode($options));

if( $courseid && $course = $DB->get_record( 'course', array( 'id' => $courseid ) ) ){
	$PAGE->set_context(context_course::instance($course->id));
}else{
	$PAGE->set_context(context_system::instance());
}

//static $count = 0;
//$count++;
//$now = time();
//$uid = $USER->id;
//$id = 'filter_con_' . rand(); //we need something unique because it might be stored in text cache


$sizes = array(
    "medium" => "_md",
    "med" => "_md",
    "md" => "_md",
    "_md" => "_md",
    "small" => "_sm",
    "sml" => "_sm",
    "sm" => "_sm",
    "_sm" => "_sm",
    "block" => "_sm",
    "sidebar" => "_sm"
);
$types = array("meeting" => "meeting", "content" => "presentation");
$breaks = array("_md" => "<br/>", "_sm" => "<br/>");

$thisdir = $CFG->wwwroot . '/filter/connect';


$iconsize = '';
$iconalign = 'left';
$silent = false;
$telephony = true;
$mouseovers = true;
$allowguests = false;
$viewlimit = '';

if (isset($element[0])) {
    $iconopts = explode("-", strtolower($element[0]));
    $iconsize = empty($iconopts[0]) ? '' : $iconopts[0];
    if (isset($iconopts[1])) {
        $silent = strpos($iconopts[1], 's') !== false; // no text output
        $autoarchive = strpos($iconopts[1], 'a') === false; // point to the recording unless the 'a' is included
        $telephony = strpos($iconopts[1], 'p') === false; // no phone info
        $allowguests = strpos($iconopts[1], 'g') !== false; // allow guest user access
        //$mouseovers = strpos($iconopts[1], 'm') === false; // no mouseover
        if (strpos($iconopts[1], 'l') !== false) $iconalign = 'left';
        elseif (strpos($iconopts[1], 'r') !== false) $iconalign = 'right';
    }
}
if (!$CFG->connect_telephony)
    $telephony = false;
//if (!$CFG->connect_mouseovers)
//    $mouseovers = false;

// RT_START RT-652 - moved the following line from it's previous location on line 66, needs to be after where $autoarchive is initially set
$autoarchive = $frommymeetings ? false : $autoarchive;
// RT_END

$startdate = empty($element[1]) ? '' : $element[1];
$enddate = empty($element[2]) ? '' : $element[2];
$extra_html = empty($element[3]) ? '' : $element[3];
$force_icon = empty($element[4]) ? '' : $element[4];
$connectid = empty($element[5]) ? 0 : $element[5];
$grouping = '';

if (!$PAGE->user_allowed_editing()) {
    if (!empty($startdate) and time() < strtotime($startdate)) return;
    if (!empty($enddate) and time() > strtotime($enddate)) return;
} else $nomouseover = false;

if (!$sco) {
    $sco = connect_get_sco_by_url($acurl);
    if (!$sco || $sco == 'no-data' || ( isset( $sco->fixedurl ) ) ) {
    	if( isset( $_SESSION['refined_noauth'] ) && $_SESSION['refined_noauth'] ){
    		echo '<div style="text-align:' . $iconalign . ';">'
	            . get_string('rs_expired_message', 'filter_connect')
	            . '</div>';
	        die;
    	}else{
	        echo '<div style="text-align:' . $iconalign . ';"><img src="' . $CFG->wwwroot
	            . '/filter/connect/images/notfound.gif"/><br/>'
	            . get_string('notfound', 'filter_connect')
	            . '</div>';
	        die;
    	}
    }
}

if ($connectid) {
    if ($connect = $DB->get_record('connect', array('id' => $connectid))) {
        if ($connect->start) {
            $sco->end = $connect->start + $connect->duration;
            $sco->start = $connect->start;
        }elseif ($connect->eventid AND $event = $DB->get_record('event', array('id' => $connect->eventid))) {
            $sco->start = $event->timestart;
            $sco->end = $event->timestart + $event->timeduration;
        }
        if ($sco->end > time()) unset($sco->archive);
        if ($connect->maxviews) {
            if (!$views = $DB->get_field('connect_entries', 'views', array('connectid' => $connect->id, 'userid' => $USER->id))) $views = 0;
            $viewlimit = get_string('viewlimit', 'filter_connect') . $views . '/' . $connect->maxviews . '<br/>';
        }
        
        // Check for grouping
        $grouping = '';
        $mod = get_coursemodule_from_instance('connect', $connect->id, $connect->course);
        if (!empty($mod->groupingid) && has_capability('moodle/course:managegroups', context_course::instance($mod->course))) {
        	$groupings = groups_get_all_groupings($mod->course);
            $textclasses = isset( $textclasses ) ? $textclasses : '';
        	$grouping = html_writer::tag('span', '('.format_string($groupings[$mod->groupingid]->name).')',
        			array('class' => 'groupinglabel '.$textclasses));
        }
        
        // check for addin launch settings
        if( isset( $CFG->connect_adobe_addin ) && $CFG->connect_adobe_addin && isset( $connect->addinroles ) && $connect->addinroles ){
        	$forceaddin = 1;
        	$roleids = explode( ',', $connect->addinroles );
        	$userroles = get_user_roles( context_course::instance( $connect->course ), $USER->id );
        	foreach( $userroles as $userrole ){
        		if( in_array( $userrole->roleid, $roleids ) ){
        			$forceaddin = 2; // one of there roles is marked to launch from browser
        			break;
        		}
        	}
        }
    }
}

// Custom icon from activity settings
if ($connectid and !empty($force_icon)) {
    // get the custom icon file url
    // TODO consider storing file name in display so as not to fetch it from the database here
    if ($cm = get_coursemodule_from_instance('connect', $connectid, $courseid, false)) {
        $context = context_module::instance($cm->id);
        $fs = get_file_storage();
        if ($files = $fs->get_area_files($context->id, 'mod_connect', 'content', 0, 'sortorder', false)) {
            $iconfile = reset($files);

            $filename = $iconfile->get_filename();
            $path = "/$context->id/mod_connect/content/0";
            $iconurl = moodle_url::make_file_url('/pluginfile.php', "$path/$filename");
            $iconsize = '';
            $icondiv = 'force_icon';
        }
    }

    // Custom icon from editor has the url in the force icon but no connect id
} else if (!$connectid and !empty($force_icon)) {
    $iconurl = $force_icon;
    $iconsize = '';
    $icondiv = 'force_icon';
}

// No custom icon, see if there is a custom default for this type
if (empty($iconurl) && isset($connect->{'type'}) && in_array($connect->{'type'}, array('meeting', 'cquiz', 'slide'))) {
    $icontype = $connect->{'type'};
    if ($icontype == 'cquiz') $icontype = 'quiz';
    if ($icontype == 'slide') $icontype = 'slideshow';
    $iconsize = isset($sizes[$iconsize]) ? $sizes[$iconsize] : '';

    $context = context_system::instance();
    $fs = get_file_storage();
    if ($files = $fs->get_area_files($context->id, 'mod_connect', $icontype . '_icon', 0, 'sortorder', false)) {
        $iconfile = reset($files);

        $filename = $iconfile->get_filename();
        $path = "/$context->id/mod_connect/{$icontype}_icon/0";
        $iconurl = moodle_url::make_file_url('/pluginfile.php', "$path/$filename");
        $icondiv = $icontype . '_icon' . $iconsize;

        if ($iconsize == '_md') {
            $iconforcewidth = 120;
        } elseif ($iconsize == '_sm') {
            $iconforcewidth = 60;
        } else {
            $iconforcewidth = 180;
        }

    }
}

// No custom icon so just display the default icon
if (empty($iconurl)) {
    $icontype = isset($types[$sco->type]) ? $types[$sco->type] : 'misc';
    if ($autoarchive AND !empty($sco->archive)) $icontype = 'archive';
    $iconsize = isset($sizes[$iconsize]) ? $sizes[$iconsize] : '';
    $iconurl = new moodle_url("/filter/connect/images/$icontype$iconsize.jpg");
    $icondiv = $icontype . '_icon' . $iconsize;
}

$strtime = '';
if ($sco->type == 'meeting' AND $sco->end > time()) {
    $strtime .= userdate($sco->start, '%a %b %d, %Y', $USER->timezone);
    if ($iconsize == '_md' OR $iconsize == '_sm') $strtime .= "<br/>";
    $strtime .= userdate($sco->start, "@ %I:%M%p") . ' - ';
    $strtime .= userdate($sco->end, "%I:%M%p ") . _tzabbr() . '<br/>';
}

$strtele = '';
if ($sco->type == 'meeting' AND $telephony AND $sco->end > time()) {
    $strtele .= '<b>';
    if (!empty($sco->phone)) {
        $strtele .= get_string('tollfree', 'filter_connect') . ' ' . $sco->phone;
        if ($iconsize == '_md' OR $iconsize == '_sm') $strtele .= "<br/>";
    }
    if (!empty($sco->pphone)) $strtele .= ' (' . get_string('pphone', 'filter_connect') . ' ' . $sco->pphone . ')';
    $strtele .= '</b><br/>';
}

if (!$silent) {
    $font = '<font>';
    if ($iconsize == '_sm') {
        $font = '<font size="1">';
    }
    $instancename = html_writer::tag('span', $sco->name, array('class' => 'instancename')) . '<br/>';
    $aftertext = $font . $instancename . $strtime . $strtele . $viewlimit . $grouping . $extra_html . '</font>';
} else {
	$instancename = html_writer::tag('span', $sco->name, array('class' => 'instancename')) . '<br/>';
	$aftertext = $font . $instancename . $strtime . $strtele . $extra_html . '</font>';
}

$archive = '';
if ($autoarchive AND !empty($sco->archive)) $archive = '&archive=' . $sco->archive;

if( !isset( $forceaddin ) || !$forceaddin ){
	$forceaddin = 0;
}
$linktarget = $forceaddin == 1 ? '_self' : '_blank';

$link = $thisdir . '/launch.php?acurl=' . $acurl . $archive . '&guests=' . ($allowguests ? 1 : 0) . '&course=' . $courseid.'&forceaddin='.$forceaddin;

$overtext = '';
if ($mouseovers || is_siteadmin($USER)) {
	$overtext = '<div align="right"><br /><br /><br />';
//        $overtext = '<b><center>' . $sco->name . '</b><br/><hr width="90%"></center></b>';
    //$overtext .= '<div align="left"><a href="' . $link . '" target="'.$linktarget.'" >';
    //if (!empty($archive) || $frommyrecordings) $overtext .= '<b>' . get_string('launch_archive', 'filter_connect') . '</a></b><br/>';
    //else $overtext .= '<b>' . get_string('launch_' . $sco->type, 'filter_connect') . '</a></b><br/>';

    if (!empty($sco->desc)) {
        $search = '/\[\[user#([^\]]+)\]\]/is';
        $sco->desc = preg_replace_callback($search, 'connect_filter_user_callback', $sco->desc);
        $overtext .= str_replace("\n", "<br />", $sco->desc) . '<br/>';
    }
    //$overtext .= $strtime . $strtele;

    if ($PAGE->user_allowed_editing()) {
    	if( $courseid && $course = $DB->get_record( 'course', array( 'id' => $courseid ) ) ){
    		$editcontext = context_course::instance($course->id);
    	}else{
    		$editcontext = context_system::instance();
    	}
        if (has_capability('filter/connect:editresource', $editcontext)) {
            $overtext .= '<a href="' . $link . '&edit=' . $sco->id . '&type=' . $sco->type . '" target="'.$linktarget.'" >';
            //$overtext .= '<img src="' . $CFG->wwwroot . '/filter/connect/images/adobe.gif" border="0" align="middle"> ';
            //$overtext .= get_string('launch_edit', 'filter_connect') . '</a><br/>';
	        $overtext .= "<img src='" . $OUTPUT->pix_url('/t/edit') . "' class='iconsmall' title='" . get_string('launch_edit', 'filter_connect')  ."' />". "</a>";
        }

        $overtext .= empty($sco->views) ? '' : '<br />(' . $sco->views . ' ' . get_string('views', 'filter_connect') . ')<br/>';

        if ($sco->type == 'meeting') {
            if ($sco->start > time()) {
            	if( !$frommymeetings ){
// 	                $overtext .= '<a href="mailto:?subject=' . rawurlencode(get_string('mailsubject', 'filter_connect', $sco));
// 	                $overtext .= '&body=' . rawurlencode(get_string('mailbody', 'filter_connect', $sco)) . '">';
// 	                $overtext .= '<img src="' . $CFG->wwwroot . '/filter/connect/images/mail.gif" border="0" align="middle"> ' . get_string('mailattendees', 'filter_connect') . '</a>';
            	}
            } else {
                $overtext .= '<a href="' . $CFG->wwwroot . '/filter/connect/attendees.php?acurl=' . $acurl . '&course=' . $courseid . '">';
                //$overtext .= '<img src="' . $CFG->wwwroot . '/filter/connect/images/attendee.gif" border="0" align="middle"> ' . get_string('viewattendees', 'filter_connect') . '</a>';
	            $overtext .= "<img src='" . $OUTPUT->pix_url('/t/groups') . "' class='iconsmall' title='" . get_string('viewattendees', 'filter_connect') ."' />". "</a>";
	            $overtext .= '<a href="' . $CFG->wwwroot . '/mod/connectmeeting/past_sessions.php?acurl=' . $acurl . '&course=' . $courseid . '">';
                //$overtext .= '<br /><img src="' . $CFG->wwwroot . '/filter/connect/images/attendee.gif" border="0" align="middle"> ' . get_string('viewpastsessions', 'filter_connect') . '</a>';
	            $overtext .= "<img src='" . $OUTPUT->pix_url('/t/calendar') . "' class='iconsmall' title='" . get_string('viewpastsessions', 'filter_connect') ."' />". "</a>";
            }
        }
    }
    $overtext .= '</div>';
}

$clock = '';
if ($sco->type == 'meeting' AND time() > ($sco->start - 1800) AND $sco->end > time()) {
    $clock = '<img id="tooltipimage" class="clock" src="' . $CFG->wwwroot . '/filter/connect/images/clock';
    if ($iconsize == '_sm') $clock .= '-s';
    $clock .= '.gif" border="0" id="clock"' . $link . '>';
    // do qtip here
}

$height = (isset($CFG->connect_popup_height) ? 'height=' . $CFG->connect_popup_height . ',' : '');
$width = (isset($CFG->connect_popup_width) ? 'width=' . $CFG->connect_popup_width . ',' : '');

$font = '';
if ($iconsize == '_sm') $font = '<font size="1">';

$onclick = $link;
$onclick = str_replace("'", "\'", htmlspecialchars($link));
$onclick = str_replace('"', '\"', $onclick);
if( $linktarget == '_self' ){
	$onclick = "window.location.href='$onclick'";
}else{
	$onclick = ' onclick="return window.open(' . "'" . $onclick . "' , 'connect', '{$height}{$width}menubar=0,location=0,scrollbars=0,resizable=1' , 0);" . '"';
}

$iconwidth = (isset($iconforcewidth)) ? "width=\"$iconforcewidth\" " : "";
$iconheight = (isset($iconforceheight)) ? "height=\"$iconforceheight\" " : "";

ajax_check_captured_output();

ob_start();
require '../views/connect_callback.html.php';
return ob_get_flush();
