<?php // $Id: filter.php,v 1.38.2.4 2009/10/26 12:00:00 terryshane Exp $
//////////////////////////////////////////////////////////////
//  Connect Pro plugin filtering for Refined Training
//  Written by Terry Shane @ Refined Data Solutions Inc. <terry@refineddata.com>
//  www.refineddata.com
//  Tel: +1.416.464.3110
//
//  This filter will replace any tags to Connect Pro urs or to flv
//  media files with code that will launch the appropriate Connect content
//
//  It also allows the use of [[user#fieldname]] tags for content customization
//
//  To activate this filter, add a line like this to your
//  list of filters in your Filter configuration:
//
//////////////////////////////////////////////////////////////

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/filelib.php');
require_once($CFG->dirroot . '/local/connect/lib.php');
require_once($CFG->dirroot . '/filter/connect/lib.php');
if (file_exists($CFG->dirroot . '/local/core/lib.php')) {
    require_once($CFG->dirroot . '/local/core/lib.php');
}

class filter_connect extends moodle_text_filter {

    function filter($text, array $options = array()) {
        global $CFG, $PAGE, $DB, $thiscourseid;

//        if (isset($CFG->hidesection)) return;

        if ($coursectx = $this->context->get_course_context(false)) $thiscourseid = $coursectx->instanceid;
        else $thiscourseid = SITEID;
        if (!is_string($text)) return $text; // nothing to do
        if (stripos($text, ']]') === false) return $text; // All below end in ]]
        
        $newtext = $text; // fullclone is slow and not needed here

        // allow $USER->variable substitution in text areas.
        $search = '/\[\[user#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_user_callback', $newtext);

        // allow $USER->variable substitution in text areas.
        $search = '/\[\[course#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_course_callback', $newtext);

        // only return content if the user has at least one of the comma-separated roles
//        $search = '/\[\[role#([^\]]+)\]\]/is';
//        $newtext = preg_replace_callback($search, 'connect_filter_role_callback', $newtext);

        // only return content if the user is / isn't a new user
//        $search = '/\[\[usertype#([^\]]+)\]\]/is';
//        $newtext = preg_replace_callback($search, 'connect_filter_usertype_callback', $newtext);

        // only return content if the user language matches
        $search = '/\[\[lang#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_lang_callback', $newtext);

        // only return block if the user language matches
//        $search = '/\[\[langblock#([^\]]+)\]\]/is';
//        $newtext = preg_replace_callback($search, 'connect_filter_langblock_callback', $newtext);

        // link to a Tutor Session content on the Adobe server
        $search = '/\[\[tconnect#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_tconnect_callback', $newtext);

        // link to a Connect Pro content on the Adobe server
        $search = '/\[\[connect#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_connect_callback', $newtext);

        // create a list of all meetings I'm enrolled in on the Adobe server
        $search = '/\[\[mymeetings#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_mymeetings_callback', $newtext);

        // create a list of all recordings for meetings I'm enrolled in on the Adobe server
        $search = '/\[\[myrecordings#([^\]]+)\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_myrecordings_callback', $newtext);

        if (isset($CFG->connect_oldplayer) AND $CFG->connect_oldplayer) {
            $search = '/\[\[flashvideo#([^\]]+)\]\]/is';
            $newtext = preg_replace_callback($search, 'connect_filter_oldflv_callback', $newtext);
        } else {
            $search = '/\[\[flashvideo#([^\]]+)\]\]/is';
            $newtext = preg_replace_callback($search, 'connect_filter_flv_callback', $newtext);
        }

        // allow enrollment expiry substitution in text areas.
        $search = '/\[\[expiredate\]\]/is';
        $newtext = preg_replace_callback($search, 'connect_filter_user_enrollment_expirydate_callback', $newtext);

        if (empty($newtext) or $newtext === $text) {
            // error or not filtered
            unset($newtext);
            return $text;
        }

        return $newtext;
    }
}

// User substitutions
function connect_filter_user_callback($link) {
    global $CFG, $USER, $PAGE;
    $disallowed = array('password', 'aclogin', 'ackey');

    $PAGE->set_cacheable(false);
    // don't show any content to users who are not logged in using an authenticated account
    if (!isloggedin()) return;

    if (!isset($USER->{$link[1]}) || in_array($link[1], $disallowed)) return;

    return $USER->{$link[1]};
}

// Course substitutions
function connect_filter_course_callback($link) {
    global $CFG, $COURSE, $DB, $filtercourse, $thiscourseid;
    if (empty($filtercourse->id) || ( $filtercourse->id != $thiscourseid)){
        $filtercourse = $DB->get_record('course', array('id'=>$thiscourseid));
        if (file_exists($CFG->dirroot . '/local/coursefields/version.php')){
            require_once($CFG->dirroot . '/local/coursefields/lib.php');
            coursefields_set_course( $filtercourse );
        }
    }
    if (empty($link[1])) return;
    $field = strtolower($link[1]);
    
    if (!isset($filtercourse->$field)) return;

    if (strpos($filtercourse->$field, '[[')) {
        $labelformatoptions = new stdClass();
        $labelformatoptions->noclean = true;
        $labelformatoptions->overflowdiv = true;
        $labelformatoptions->nocache = true;
        $content = format_text($filtercourse->$field, FORMAT_HTML, $labelformatoptions);
    } else $content = $filtercourse->$field;

    return $content;
}

//function connect_filter_role_callback($link) {
//    global $CFG, $USER, $PAGE;
//
//    $element = explode("#", $link[1]);
//    $rec = new stdClass;
//    $rec->rolefilter = $element[0];
//    if (!rolefilter($rec)) $CFG->hidesection = true;
//    return;
//}

//function connect_filter_usertype_callback($link) {
//    global $CFG, $USER, $PAGE, $DB;
//`
//    $now = time();
//    $element = explode("#", $link[1]);
//    $usertype = strtolower($element[0]);
//    $timelimit = (isset($element[1]) ? (int)$element[1] : 48) * 3600;
//
//    if (!isset($USER->firstaccess) OR !$USER->firstaccess) {
//        $rec = new StdClass;
//        $rec->id = $USER->id;
//        $rec->firstaccess = $now;
//        $USER->firstaccess = $now;
//        $DB->update_record('user', $rec);
//    }
//    $delta = $now - $USER->firstaccess;
//
//    if (($usertype == 'new' AND $delta >= $timelimit)
//        OR ($usertype == 'old' AND $delta <= $timelimit)
//    ) $CFG->hidesection = true;
//
//    return;
//}

function connect_filter_lang_callback($link) {
    global $CFG, $USER;

    $element = explode("#", $link[1]);
    $lang = $element[0];
    $content = $element[1];
    if (empty($lang) OR empty($content)) return;

    if ($lang == current_language()) return $content;

    return;
}

//function connect_filter_langblock_callback($link) {
//    global $CFG, $USER, $PAGE;
//
//    $mouseovers = true;
//    $element = explode("#", $link[1]);
//    $lang = $element[0];
//    if ($lang != current_language()) $CFG->hidesection = true;
//
//    return;
//}

// Added for tsessions.  Translates to a connect link
function connect_filter_tconnect_callback($link) {
    global $CFG, $USER, $thiscourseid, $DB, $PAGE;

    $mouseovers = true;

    $PAGE->set_cacheable(false);
    if (!isloggedin() || $USER->username == 'guest') return;

    static $count = 0;
    $count++;
    $now = time();
    $uid = $USER->id;
    $id = 'filter_flv_' . rand(); //we need something unique because it might be stored in text cache

    $element = explode("#", $link[1]);
    //$acurl       = $element[0];
    $acurl = strip_tags($element[0]);
    $now = time();
    $iconalign = 'center';
    $iconsize = 'large';

    if (isset($element[1])) {
        $iconopts = explode("-", strtolower($element[1]));
        $iconsize = empty($iconopts[0]) ? 'large' : $iconopts[0];
        if (isset($iconopts[1])) {
            if (strpos($iconopts[1], 'l') !== false) $iconalign = 'left';
            if (strpos($iconopts[1], 'r') !== false) $iconalign = 'right';
        }
    }

    // Setup variables
    $icondir = $CFG->wwwroot . '/mod/tsession/lang/en/icons/';
    if (file_exists($CFG->dirroot . '/mod/tsession/lang/' . $CFG->lang . '/icons/')) {
        $icondir = $CFG->wwwroot . '/mod/tsession/lang/' . $CFG->lang . '/icons/';
    }
    $icon_end = ".jpg";
    $div_end = '';
    if ($iconsize == "small") {
        $icon_end = "_sm.jpg";
        $div_end = "_s";
    }

    // Get information about this acurl
    if (!$tsession = $DB->get_record('tsession', array('course' => $thiscourseid, 'acurl' => $acurl))) {
        return '<h2><strong>' . get_string('notfound', 'tsession') . '</strong></h2>';
    }

    $tsession_link = '';
    if ($tutor = $DB->get_record('tsession_tutors', array('course' => $thiscourseid, 'userid' => $USER->id))) {
        $tuser = $USER;
        if (!isset($slot)) $slot = new stdClass();
        $slot->start = time() + 3600;
    } else {
        if (!$slot = $DB->get_record('tsession_slots', array('tsessionid' => $tsession->id, 'studentid' => $USER->id))) {
            $tutor = $DB->get_record_select('tsession_tutors', "course={$thiscourseid} AND students LIKE '%-{$USER->id}-%' LIMIT 1");
            if (!isset($tutor->id) AND isset($CFG->tsession_autotutor) AND $CFG->tsession_autotutor) {
                if ($DB->count_records('tsession_tutors', array('course' => $thiscourseid)) == 1) {
                    $tutor = $DB->get_record('tsession_tutors', array('course' => $thiscourseid));
                    $tutor->students .= $USER->id . '-';
                    $DB->update_record('tsession_tutors', $tutor);
                }
            }
            if (isset($tutor->id)) {
                $schedicon = $icondir . 'tsession_schedule' . $icon_end;
                $tsession_link = $CFG->wwwroot . '/mod/tsession/schedule.php?course=' . $thiscourseid . '&sess=' . $tsession->id . '&id=' . $USER->id . '&ret=1';
                return '<div align="' . $iconalign . '">' . '<a href="' . $tsession_link . '"><img src="' . $schedicon . '"></a>' . '</div>';
            } else {
                $notuticon = $icondir . 'tsession_notutor' . $icon_end;
                return '<div align="' . $iconalign . '">' . '<img src="' . $notuticon . '">' . '</div>';
            }
        }
        if (isset($CFG->tsession_schedpercrs) AND $CFG->tsession_schedpercrs) {
            if ((!$tutor = $DB->get_record('tsession_tutors', array('id' => $slot->tutorid)))
                OR (!$tuser = $DB->get_record('user', array('id' => $tutor->userid)))
            ) {
                return '<h2><strong>Configuration problem with tsessions</strong></h2>';
            }
        } else {
            if ((!$tutor = $DB->get_record('tsession_tutors', array('course' => $thiscourseid, 'userid' => $slot->tutorid)))
                OR (!$tuser = $DB->get_record('user', array('id' => $slot->tutorid)))
            ) {
                return '<h2><strong>Configuration problem with tsessions</strong></h2>';
            }
        }
    }

    // Setup configuration variables
    $description = '<br />' . get_string('notfound', 'tsession');

    // Specific URL Variables
    $recording = '';
    $inprogress = '';
    if (!empty($slot)) {
        if (($slot->start + $CFG->tsession_slotsize) < $now) $recording = '_recording';
        elseif (($slot->start - 600) < $now) $inprogress = '_inprogress';
    }

    $url = $tutor->acurl;
    // If recording
    if (!empty($recording)) {
        if (empty($slot->recording)) {
            $slot->recurl = connect_get_recording($tutor->ucurl, $slot->start, $slot->start + $slot->duration);
            if (empty($slot->recurl)) $slot->recurl = 'none';
            $DB->update_record('tsession_slots', $slot);
        }
        if (empty($slot->recurl) OR $slot->recurl == 'none') {
            $norecicon = $icondir . 'tsession_norecording' . $icon_end;
            return '<div align="' . $iconalign . '">' . '<img src="' . $norecicon . '">' . '</div>';
        }
        $url = $slot->recurl;
    }

    $icon = $icondir . 'tsession' . $recording . $inprogress . $icon_end;
    $div = 'meeting_icon' . $div_end;

    $link = $CFG->wwwroot . '/filter/connect/launch.php?acurl=' . $url . '&guests=0&course=' . $thiscourseid;

    $text = '<div align="' . $iconalign . '">';
    $text .= '<div align="' . $iconalign . '" id="' . $div . '">';
    $text .= '<a href="' . $link . '" target="_blank"><img src="' . $icon . '"  border="0"  id="launch" ' . $link . '></a>';
    $text .= '</div><div id="acpopup"></div>';
    $text .= html_writer::tag('span', $tsession->name, array('class' => 'instancename hide'));
    $text .= '</div>';

    $overtext = '';
    if (!$sco = connect_get_sco_by_url($url)) {
        return '<div align="' . $iconalign . '"><img src="' . $CFG->wwwroot
        . '/filter/connect/images/notfound.gif"/><br/>'
        . get_string('notfound', 'filter_connect')
        . '</div>';
    }

    $aftertext = '';
    if ($slot = $DB->get_record('tsession_slots', array('tutorid' => $tsession->id, 'studentid' => $USER->id))) {
        $aftertext .= $tsession->name . '<br />';
        $tutor = $DB->get_record('user', array('id' => $slot->tutorid));
        $aftertext .= fullname($tutor) . '<br />';
        $aftertext .= userdate($slot->start) . '<br />';
    }


    if ($mouseovers) {
        $overtext = '<b><center>' . $sco->name . '</b><br/><hr width="90%"></center></b>';
        $overtext .= '<div align="left"><a href="' . $link . '" target="_blank" >';
        if (!empty($archive)) $overtext .= '<b>' . get_string('launch_archive', 'filter_connect') . '</a></b><br/>';
        else $overtext .= '<b>' . get_string('launch_' . $sco->type, 'filter_connect') . '</b></a><br/><br />';
        $tsession_link = $CFG->wwwroot . '/mod/tsession/schedule.php?course=' . $thiscourseid;
        $overtext .= '<a href="' . $tsession_link . '" >' . '<b>' . get_string('bookasession', 'tsession') . '</b></a><br/>';
        $overtext .= '</div>';
    }

    $clock = '';

    $height = (isset($CFG->connect_popup_height) ? 'height=' . $CFG->connect_popup_height . ',' : '');
    $width = (isset($CFG->connect_popup_width) ? 'width=' . $CFG->connect_popup_width . ',' : '');

    $font = '';
    if ($iconsize == '_sm') $font = '<font size="1">';

    $onclick = $link;
    $onclick = str_replace("'", "\'", htmlspecialchars($link));
    $onclick = str_replace('"', '\"', $onclick);
    $onclick = ' onclick="return window.open(' . "'" . $onclick . "' , 'connect', '{$height}{$width}menubar=0,location=0,scrollbars=0,resizable=1' , 0);" . '"';

    $text = '';
    if (!empty($overtext)) {
        $text .= '<script type="text/javascript">
    			 $(document).ready(function()
				 {
					 // MAKE SURE YOUR SELECTOR MATCHES SOMETHING IN YOUR HTML!!!
					 $(\'#tooltipanchor,#tooltipimage\').each(function() {
						 $(this).qtip({
							 content: {
								 text: $(this).next(\'.tooltiptext\')
							 },
							 position: {
								 target: \'mouse\',
								 adjust: { mouse: false },
								 viewport: $(window)
							 },
							 hide: {
								 fixed: true,
								 delay: 300
							 }
						 });
					 });
				 });</script>';
    }
    $text .= '<div align="' . $iconalign . '">';
    $text .= '<div align="' . $iconalign . '">';

    $text .= '<a id="tooltipanchor" href="javascript:void(0);"><img src="' . $icon . '"  border="0"  id="launch" ' . $onclick . '>' . $clock . '</a>';
    $text .= "<div style='display: none;' class=\"tooltiptext\">" . $overtext . "</div>";

    // do qtip here
//    $text .= '<a href="' . $link . '" target="_blank"><img src="' . $iconurl . '"  border="0"  id="launch" ' . $overtext . $onclick . '>' . $clock . '</a>';
    $text .= '</div><div id="acpopup"></div>';
    $text .= $aftertext;
    $text .= '</div>';

    return $text;

}

// call this function with just the Connect pro URL by default - no other parameters are required
// optionally, pass the icon size small|medium|large (add-s to suppress text output)
// you can pass optional start and end dates that determine visibility.
// dates are passed in the format YYYYMMDDHHSS and shorter strings will be padded with zeros eg 20080512 is OK
// anything passed in the 5th position "element[4]" will be output as unevaluated text

function connect_filter_connect_callback($link, $sco = false, $frommymeetings = 0, $frommyrecordings = 0) {
    global $CFG, $USER, $PAGE, $thiscourseid, $DB;

    if (!isloggedin() || $USER->username == 'guest') return;

    // Parse String to get Options
    $element = explode("#", $link[1], 2);
    $acurl = strip_tags($element[0]);
    $options = isset($element[1]) ? str_replace('#','~',$element[1]) : null;

    return '<div class="connect_filter_block" data-courseid="' . $thiscourseid . '" data-acurl="' . $acurl . '" data-sco="' . json_encode($sco) . '" data-options="' . $options . '" data-frommymeetings="'.$frommymeetings.'" data-frommyrecordings="'.$frommyrecordings.'" >'
    . '<div id="id_ajax_spin" class="rt-loading-image"></div>'
    . '</div>';
}

function connect_filter_mymeetings_callback($link) {
    global $CFG, $USER, $SITE, $thiscourseid, $DB, $PAGE;

    $PAGE->set_cacheable(false);

    if (!isloggedin() || $USER->username == 'guest') return;

//    $now = time();
//    $id = 'filter_mym_' . $now;
//    $text = '';
//    $thisdir = $CFG->wwwroot . '/filter/connect';
//    $strmymtg = get_string('mymeetings', 'filter_connect');
//    $strphone = get_string('tollfree', 'filter_connect');
//    $strcode = get_string('pphone', 'filter_connect');
//    $strrole = get_string('mtgrole', 'filter_connect');
//    $telephony = true;
//    $mouseovers = true;
//
//    if (isset($CFG->rds_connect_telephony) AND !$CFG->rds_connect_telephony) $telephony = false;
//    if (isset($CFG->rds_connect_mouseover) AND !$CFG->rds_connect_mouseover) $mouseovers = false;

    // Calculate how many days of meetings to return
    $element = explode("#", $link[1]);
    $days = empty($element[0]) ? 15 : (int)$element[0];

    return '<div class="connect_filter_mymeetings_block" data-days="' . $days . '" style="text-align:center;">'
    . '<div id="id_ajax_spin" class="rt-loading-image"></div>'
    . '</div>';

//    // Loop through each meeting
//    if ($meetings = connect_mymeetings($days)) {
//        $text .= '<table border="0" cellpadding="4" cellspacing="0" align="center">';
//        $text .= '<tr><td colspan="6" align="center"><br /><b>' . $strmymtg . '</b></td></tr>';
//        $text .= '<tr><td colspan="2" align="center"><hr width="100%"></td></tr>';
//
//        foreach ($meetings as $meeting) {
//            $link = $thisdir . '/launch.php?acurl=' . $meeting->url . '&mtg=y target="_blank"';
//            $time_info = '<br />' . userdate(strtotime($meeting->start), "%a %b %d, %Y @ %I:%M%p ") . ' - ' . userdate(strtotime($meeting->end), "%I:%M%p ") . _tzabbr();
//            $phone_info = '';
//
////            if ($telephony AND !empty($meeting->phone)) {
////                $phone_info = "<br /><b>" . $strphone . " " .
////                    $meeting->phone . "&nbsp;&nbsp;(" . $strcode . " " .
////                    $meeting->pphone . " #)</b>";
////            }
//
//            $sublink = array('', $meeting->url . '#small-ls');
//            $text .= '<tr><td>' . connect_filter_connect_callback($sublink) . '</td>';
//
//            $text .= '<td valign="middle" align="center"><b>' . $meeting->name . '</b>';
//            $text .= $time_info . $phone_info;
//
////            if ($meeting->role != 'Participant') $text .= '<br />' . $strrole . ': ' . $meeting->role;
//            $text .= '</tr>';
//        }
//        $text .= '</table><br />';
//    }
//    return $text;

}

//Lists recordings for each connect activity in course which user has access to
function connect_filter_myrecordings_callback($link) {
    global $CFG, $USER, $SITE, $thiscourseid, $DB, $PAGE;

    $PAGE->set_cacheable(false);
    if (!isloggedin() || $USER->username == 'guest') return;

    $text = ' ';
    $strmyrec = get_string('myrecordings', 'filter_connect');

    // Calculate how many days of recordings to return
    $element = explode("#", $link[1]);
    $days = empty($element[0]) ? 1000 : (int)$element[0];
    $block = empty($element[1]) ? '' : 'block';
    $filter = '';
    $firstdate = 0;
    if ($days) $firstdate = time() - $days * 24 * 60 * 60;
    $thiscourseid = (int)$thiscourseid;


    // Determine list of urls user has access to
    $urls = $DB->get_records_sql_menu("SELECT DISTINCT c.id, c.url
                                        FROM {$CFG->prefix}rtrecording c, {$CFG->prefix}course_modules cm, {$CFG->prefix}modules m
                                        WHERE c.id              = cm.instance
                                        AND cm.module           = m.id
                                        AND m.name              = 'rtrecording'
                                        AND cm.course           = {$thiscourseid}
                                        UNION
                                        SELECT DISTINCT c.id, c.url
                                        FROM {$CFG->prefix}rtrecording c, {$CFG->prefix}course_modules cm, {$CFG->prefix}modules m, {$CFG->prefix}groupings_groups gg, {$CFG->prefix}groups_members gm
                                        WHERE c.id              = cm.instance
                                        AND cm.module           = m.id
                                        AND m.name              = 'rtrecording'
                                        AND cm.groupingid       = gg.groupingid
                                        AND gg.groupid          = gm.groupid
                                        AND cm.course           = {$thiscourseid}
                                        AND gm.userid           = {$USER->id}
                                        UNION
                                        SELECT DISTINCT c.id, c.ac_archive as url
                                        FROM {$CFG->prefix}connectmeeting c, {$CFG->prefix}course_modules cm, {$CFG->prefix}modules m
                                        WHERE c.id              = cm.instance
                                        AND cm.module           = m.id
                                        AND m.name              = 'connectmeeting'                                        
                                        AND cm.course           = {$thiscourseid}
                                        AND (c.ac_archive != '' AND c.ac_archive is not null)
                                        ");
    if (!$urls) return $text;

    // Loop through each recording
    if ($recordings = connect_get_recordings($urls, $firstdate)) {
        //$text .= '<table border="0" cellpadding="4" cellspacing="0" align="center">';
        if (empty($block)) {
            //$text .= '<tr><td colspan="6" align="center"><br /><b>' . $strmyrec . '</b></td></tr>';
            //$text .= '<tr><td colspan="2" align="center"><hr width="100%"></td></tr>';
        }

        foreach ($recordings as $recording) {
            //$link = $CFG->wwwroot . '/filter/connect/launch.php?acurl=' . $recording->url . '&mtg=y target="_blank"';
            //$time_info = '<br />' . userdate($recording->start, "%a %b %e, %Y @ %I:%M%p") . ' - ' . userdate($recording->end, "%I:%M%p ") . _tzabbr();

            $sublink = array('', $recording->url . '#medium-ls');
            //$text .= '<tr><td>' . connect_filter_connect_callback($sublink, $recording, 0, 1) . '</td>';
            $text .= connect_filter_connect_callback($sublink, $recording, 0, 1);
            //$text .= '<td valign="middle" align="center"><b>' . $recording->name . '</b>';
            if (empty($block)) {
                //$text .= '<br/>' . $recording->parent;
               // $text .= $time_info;
            }

            //$text .= '</tr>';
        }
        //$text .= '</table>';
        //if (empty($block)) $text .= '<br/>';
    }
    return $text;

}

function connect_filter_flv_callback($link) {
    global $CFG, $USER, $SITE, $thiscourseid, $DB, $PAGE;

    $link[1] = preg_replace('/&amp;/', '&', $link[1]);

    $PAGE->set_cacheable(false);

    // RT_START Allow user not logined in to view the flash video on front page.
    //if (!isloggedin() || $USER->username == 'guest') return;
    if (!isloggedin() || $USER->username == 'guest') {
        $now = time();
        $id = 'filter_flv_' . $now;
        $element = explode("#", $link[1]);
        $width = empty($element[1]) ? '640' : $element[1];
        $height = empty($element[2]) ? '380' : $element[2];
        $height = $height + 30;
        $stillphoto = empty($element[3]) ? $CFG->connect_videophoto : $element[3];

        $optstring = '';
        if (!empty($element[4])) {
            $opts = explode('-', $element[4]);
            $optstring .= ',' . implode(',' . $opts);
        }

        if (substr(strtolower($element[0]), 0, 4) == "http") {
            $url = $element[0];
        } elseif (substr($element[0], 0, 1) == "/") $url = $CFG->connect_videoserver . substr($element[0], 1);
        else $url = $CFG->connect_videoserver . $element[0];
        $url = addslashes_js($url);
        if (!empty($stillphoto)) {
            if (substr(strtolower($stillphoto), 0, 4) !== "http") {
                if (substr($stillphoto, 0, 1) == "/") $stillphoto = $CFG->connect_videoserver . substr($stillphoto, 1);
                else $stillphoto = $CFG->connect_videoserver . $stillphoto;
            }
        }
        $stillphoto = addslashes_js($stillphoto);
        $player = '';
        if (!isset($CFG->player_fns_applied) OR !$CFG->player_fns_applied) {
            $player = '<script type="text/javascript" src="' . $CFG->wwwroot . '/filter/connect/jwplayer/jwplayer.js">//</script>';
            $player .= '<script type="text/javascript">jwplayer.key="tbl2cifGc9Zc760s2R/6NakJBWHNxwp/gdoSvA==";</script>';
            $CFG->player_fns_applied = true;
        }

        return '<div style="margin:5px;"><div class="connect connect_flv" id="' . $id . '"></div></div>' .
        $player .
        html_writer::tag('span', 'Connect video', array('class' => 'instancename hide')) .
        '<script type="text/javascript">' .
        'jwplayer("' . $id . '").setup({' .
        'flashplayer: "' . $CFG->wwwroot . '/filter/connect/jwplayer/player.flash.swf",' .
        'file: "' . $url . '",' .
        'image: "' . $stillphoto . '",' .
        'skin: "' . $CFG->wwwroot . '/filter/connect/jwplayer/six.xml",' .
        'width: "' . $width . '",' .
        'height: "' . $height . '",' .
        //'plugins: {' .
        //"'" . $CFG->wwwroot . '/filter/connect/scripts/moodleMonitor.js' . "': {" .
        //$optstring . '} } '.
        '});' .
        '</script>';
    }
    // RT_END

    static $count = 0;
    $count++;
    $now = time();
    $uid = $USER->id;
    $id = 'filter_flv_' . $uid . $now . $count; //we need something unique because it might be stored in text cache

    $element = explode("#", $link[1]);
    $width = empty($element[1]) ? '640' : $element[1];
    $height = empty($element[2]) ? '380' : $element[2];
    $height = $height + 30;
    $stillphoto = empty($element[3]) ? $CFG->connect_videophoto : $element[3];

    $optstring = '';
    if (!empty($element[4])) {
        $opts = explode('-', $element[4]);
        $optstring .= ',' . implode(',' . $opts);
    }

    if (substr(strtolower($element[0]), 0, 4) == "http") {
        $url = $element[0];
    } elseif (substr($element[0], 0, 1) == "/") $url = $CFG->connect_videoserver . substr($element[0], 1);
    else $url = $CFG->connect_videoserver . $element[0];

    $url = addslashes_js($url);

    // Check if grading of movies is required
    if (isset($thiscourseid)) {
        require_once($CFG->dirroot . '/mod/rtvideo/lib.php');
        $optstring .= rtvideo_get_movie_flashvars($thiscourseid, $url); // Add flashvars if required
    }

    if (!empty($stillphoto)) {
        if (substr(strtolower($stillphoto), 0, 4) !== "http") {
            if (substr($stillphoto, 0, 1) == "/") $stillphoto = $CFG->connect_videoserver . substr($stillphoto, 1);
            else $stillphoto = $CFG->connect_videoserver . $stillphoto;
        }
    }

    $stillphoto = addslashes_js($stillphoto);
    //$playerskin = addslashes_js( $CFG->wwwroot . '/filter/connect/scripts/playerskin.swf' );

    // by defining this variable, we trigger the inclusion of the required javascript funtions when the page is served
    $player = '';
    if (!isset($CFG->player_fns_applied) OR !$CFG->player_fns_applied) {
        $player = '<script type="text/javascript" src="' . $CFG->wwwroot . '/filter/connect/jwplayer/jwplayer.js">//</script>';
        $player .= '<script type="text/javascript">jwplayer.key="tbl2cifGc9Zc760s2R/6NakJBWHNxwp/gdoSvA==";</script>';
        $CFG->player_fns_applied = true;
    }

    return '<div style="margin:5px;"><div class="connect connect_flv" id="' . $id . '"></div></div>' .
    $player .
    html_writer::tag('span', 'Connect video', array('class' => 'instancename hide')) .
    '<script type="text/javascript">' .
    'jwplayer("' . $id . '").setup({' .
    'flashplayer: "' . $CFG->wwwroot . '/filter/connect/jwplayer/player.flash.swf",' .
    'file: "' . $url . '",' .
    'image: "' . $stillphoto . '",' .
    'skin: "' . $CFG->wwwroot . '/filter/connect/jwplayer/six.xml",' .
    'width: "' . $width . '",' .
    'height: "' . $height . '",' .
    'plugins: {' .
    "'" . $CFG->wwwroot . '/filter/connect/scripts/moodleMonitor.js' . "': {" .
    $optstring . '} } });' .
    '</script>';
}

function connect_filter_oldflv_callback($link) {
    global $CFG, $USER, $SITE, $thiscourseid, $DB;

    if (!isloggedin() || $USER->username == 'guest') return;

    static $count = 0;
    $count++;
    $now = time();
    $uid = $USER->id;
    $id = 'filter_flv_' . $uid . $now . $count; //we need something unique because it might be stored in text cache

    $element = explode("#", $link[1]);
    $width = empty($element[1]) ? '640' : $element[1];
    $height = empty($element[2]) ? '380' : $element[2];
    $height = $height + 30;
    $stillphoto = empty($element[3]) ? $CFG->connect_videophoto : $element[3];

    $optstring = '';
    if (!empty($element[4])) {
        $opts = explode('-', $element[4]);
        $optstring = '&' . implode('&' . $opts);
    }

    if (substr(strtolower($element[0]), 0, 4) == "http") {
        $url = $element[0];
    } elseif (substr($element[0], 0, 1) == "/") $url = $CFG->connect_videoserver . substr($element[0], 1);
    else $url = $CFG->connect_videoserver . $element[0];


    $streamer = "";
    if (substr(strtolower($element[0]), 0, 4) == "rtmp") {
        $rtmp = explode("-", $element[0]);
        $streamer = "&type=rtmp&autostart=true&streamer=" . $rtmp[0];
        $url = $rtmp[1];
    }

    $url = addslashes_js($url);

    // Check if grading of movies is required
    if (isset($thiscourseid)) {
        require_once($CFG->dirroot . '/mod/rtvideo/lib.php');
        $optstring .= rtvideo_get_movie_flashvars($thiscourseid, $url); // Add flashvars if required
    }

    if (!empty($stillphoto)) {
        if (substr(strtolower($stillphoto), 0, 4) !== "http") {
            if (substr($stillphoto, 0, 1) == "/") $stillphoto = '&image=' . $CFG->connect_videoserver . substr($stillphoto, 1);
            else $stillphoto = '&image=' . $CFG->connect_videoserver . $stillphoto;
        } else $stillphoto = '&image=' . $stillphoto;
        $stillphoto .= '?' . mt_rand();
    }

    $stillphoto = addslashes_js($stillphoto);
    $playerskin = addslashes_js($CFG->wwwroot . '/filter/connect/scripts/playerskin.swf');

    // by defining this variable, we trigger the inclusion of the required javascript funtions when the page is served
    $player = '';
    if (!isset($CFG->oldplayer_fns_applied) OR !$CFG->oldplayer_fns_applied) {
        $player = '<script type="text/javascript" src="' . $CFG->wwwroot . '/filter/connect/scripts/player.js"></script>';
        $CFG->oldplayer_fns_applied = true;
    }

    return $player .
    html_writer::tag('span', 'Connect video', array('class' => 'instancename hide')) .
    '<span class="connect connect_flv" id="' . $id . '"></span>' .
    '<script type="text/javascript">' .
    'var s' . $now . $count . ' = new SWFObject("' . $CFG->wwwroot . '/filter/connect/scripts/player.swf","rdsplayer' . $count . '","' . $width . '","' . $height . '","9","#ffffff"); ' .
    's' . $now . $count . '.addParam("allowfullscreen","true");' .
    's' . $now . $count . '.addParam("allowscriptaccess","always");' .
    's' . $now . $count . '.addParam("wmode","opaque");' .
    's' . $now . $count . '.addParam("flashvars","bufferlength=10&smoothing=true&file=' . $url . $stillphoto . '&skin=' . $playerskin . '&stretching=exactfit' . $streamer . $optstring . '");' .
    's' . $now . $count . '.write("' . $id . '");' .
    '</script>';
}

// User substitutions
function connect_filter_user_enrollment_expirydate_callback($link) {
    global $CFG, $USER, $COURSE, $PAGE, $DB;
    $PAGE->set_cacheable(false);
    // don't show any content to users who are not logged in using an authenticated account
    if (!isloggedin()) return;
    $query = "SELECT ue.timestart, ue.timeend FROM {user_enrolments} ue JOIN {enrol} e ON ue.enrolid = e.id WHERE ue.userid = ? AND e.courseid = ?";
    $enrol = $DB->get_record_sql($query, array($USER->id, $COURSE->id));
    if (empty($enrol) || empty($enrol->timeend)) return '-';
    $date = userdate( $enrol->timeend, '%B %d, %Y');
    return $date;
}