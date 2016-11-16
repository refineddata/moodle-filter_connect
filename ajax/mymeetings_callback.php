<?php
/**
 * connect_callback.php.
 *
 * @author     Dmitriy
 * @since      11/07/14
 */

define('AJAX_SCRIPT', true);
require_once(dirname(__FILE__) . '/../../../config.php');
//require_once($CFG->dirroot . '/mod/connectmeeting/connectlib.php');
require_once($CFG->dirroot . '/filter/connect/filter.php');
//require_once($CFG->dirroot . '/filter/connect/lib.php');

// This should be accessed by only valid logged in user.
if (!isloggedin() or isguestuser()) {
    die('Invalid access.');
}

// Start capturing output in case of broken plugins.
ajax_capture_output();

$days = required_param('days', PARAM_INT);
$thisdir = $CFG->wwwroot . '/filter/connect';

$text='';

// Loop through each meeting
if ($meetings = connect_mymeetings($days)) {
	if( isset( $_SESSION['refined_noauth'] ) && $_SESSION['refined_noauth'] ){
		echo '<div style="text-align:' . $iconalign . ';">'
	            . get_string('rs_expired_message', 'filter_connect')
	            . '</div>';
	    die;
	}
    $text .= '<table border="0" cellpadding="4" cellspacing="0" align="center">';
    $text .= '<tr><td colspan="6" align="center"><br /><b>' . get_string('mymeetings', 'filter_connect') . '</b></td></tr>';
    $text .= '<tr><td colspan="2" align="center"><hr width="100%"></td></tr>';

    $text .= "</table>";

    $text.= '<div class="mymeetings-columns">';

    $text.= '<ul style="margin: 0; padding: 0; list-style-type: none;">';

    $firstmeeting = 1;
    foreach ($meetings as $meeting) {
        $link = $thisdir . '/launch.php?acurl=' . $meeting->url . '&mtg=y target="connect"';
        $time_info = '<br />' . userdate(strtotime($meeting->start), "%a, %b %d, %Y @ %I:%M%p ") . ' - ' . userdate(strtotime($meeting->end), "%I:%M%p ") . _tzabbr();
        $phone_info = '';

//            if ($telephony AND !empty($meeting->phone)) {
//                $phone_info = "<br /><b>" . $strphone . " " .
//                    $meeting->phone . "&nbsp;&nbsp;(" . $strcode . " " .
//                    $meeting->pphone . " #)</b>";
//            }

        $sublink = array('', $meeting->url . '#small-ls');

        $firststlyeadd = '';
        if( $firstmeeting ){
            $firstmeeting = 0;
            $firststyleadd = ' style="margin-top:0px;"';
        }

        $text.= '<li style="display: inline-block; padding-left: 20px; padding-right: 20px;">';
        $text.= '<table style="width:342px">';

        $text .= '<tr><td>';
        $text .= connect_filter_connect_callback($sublink, false, 1);
        $text.= '</td>';

        $text.= '<td valign="middle" align="center">';
        $text .= '<b><a href="'.$link.'">' . $meeting->name . '</a></b>';
        $text .= $time_info . $phone_info;
        $text.= '</td></tr>';
        
        $text.= '</table>';
        $text .= '</li>';
    }

    $text.= "</ul></div>";

    $text .= '<br />';
}

ajax_check_captured_output();

ob_start();
require '../views/mymeetings_callback.html.php';
return ob_get_flush();
