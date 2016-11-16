<?php

function connect_attendance_output($acurl) {
    global $CFG, $DB;

    $sco = connect_get_sco_by_url($acurl, 1);
    $stratt = get_string('attendees', 'filter_connect');

    $str = '';
    $str .= '<br /><table border="0" id="calendar" style="height:100%;">';

    $str .= '<tr><th colspan="4" align="center"><font size="+1"><b>';
    $str .= $stratt . ' (' . get_string('meeting', 'filter_connect') . ': ' . $acurl . ')';
    $str .= '<br /><br />';
    $str .= $sco->desc;
    $str .= '<br /><br /></b></font></td></tr>';

    if (empty($sco->sessions)) $str .= '<center><h2>No sessions exist.</h2></center>';
    else {
        $i = 1;
        foreach ($sco->sessions as $session) {
            if ($i++ > 5) break;

            if (isset($session->start) AND isset($session->end) AND $session->start AND $session->end) $strtime = number_format(($session->end - $session->start) / 60, 2) . ' ' . get_string('minsabb', 'filter_connect');
            else $strtime = 'in progress';

            $str .= '<tr><td colspan="4" align="left"><b>';
            $str .= get_string('eventname', 'filter_connect') . ': ';
            //$str .= DATE('l jS \of F Y h:i:s A', $session->start);
            $str .= userdate($session->start);
            

            if (isset($session->end) AND $session->end) 
                //$str .= ' -  ' . DATE('h:i:s A', $session->end);
                $str .= ' -  ' . userdate($session->end, '%I:%M %p' );
            $str .= ' (' . $strtime . ')';
            $str .= '</b></td></tr>';
            $str .= '<tr>';
            $str .= '<td width="25%"><b><u>' . get_string('login', 'filter_connect') . '</u></b></td>';
            $str .= '<td width="25%"><b><u>' . get_string('fullname', 'filter_connect') . '</u></b></td>';
            $str .= '<td width="20%"><b><u>' . get_string('minutes', 'filter_connect') . '</u></b></td>';
            $str .= '<td width="17%"><b><u>' . get_string('entrytime', 'filter_connect') . '</u></b></td>';
            $str .= '<td width="13%">&nbsp;</td>';
            $str .= '</tr>';

            $attendeetotals = array();

            foreach ($sco->history as $attendee) {
                //error_log(json_encode($sco->history));
                if ($attendee->start < $session->start || ($session->end && $attendee->start > $session->end)) {
                    continue;
                }

                $attendee->strtime = ($attendee->end) ? number_format(($attendee->end - $attendee->start) / 60, 2) : 'Live Attendee';
                //$attendee->strstart = isset($attendee->start) ? DATE('h:i:s A (T)', $attendee->start) : 'Unknown';

                if (isset($attendeetotals[$attendee->login])) {
                    $attendeetotals[$attendee->login]->strtime += $attendee->strtime;
                    //$attendeetotals[$attendee->login]->strstart = $attendee->strstart;
                } else {
                    $attendeetotals[$attendee->login] = $attendee;
                }
            }

            foreach ($attendeetotals as $attendee) {
                $strtime = ($attendee->end) ? number_format(($attendee->end - $attendee->start) / 60, 2) : 'Live Attendee';
                //$strstart = isset($attendee->start) ? DATE('h:i:s A (T)', $attendee->start) : 'Unknown';
                $strstart = isset($attendee->start) ? userdate($attendee->start, '%I:%M:%S %p' ) : 'Unknown';
                $str .= '<tr>';
                $str .= '<td>' . $attendee->login . '</td>';
                $str .= '<td>' . $attendee->name . '</td>';
                $str .= '<td>' . $attendee->strtime . '</td>';
                $str .= '<td>' . $strstart . '</td>';
                $str .= '<td>';
                $str .= $attendee->external_user_id ? '':'Not in Moodle';
                $str .= '</td>';
                $str .= '</tr>';
            }

            $str .= '<tr><td colspan="4" align="left"><br /><hr width="100%"><br /><td></tr>';
        }
    }

    $str .= "</table><br><br>";
    return $str;
}

function _tzabbr() {
    global $USER, $CFG;
    if ($USER->timezone == 99) {
        $userTimezone = $CFG->timezone;
    } else {
        $userTimezone = $USER->timezone;
    }
    $dt = new DateTime("now", new DateTimeZone($userTimezone));
    return $dt->format('T');
}
