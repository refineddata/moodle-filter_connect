<?php // $Id: refineddata connect filter,v 1.1 2008/05/16 12:00:00 terryshane Exp $

$string['pluginname']					   = 'Connect Filter';
$string['connect:editresource']  = 'Edit resource on Adobe';

$string['editaclogin']         	 = 'Admin Edits ACLogin from Profile';
$string['editaclogin_hint']    	 = '<font size=\"1\">Set this to on if you want administrators to be able to directly enter AC Logins onto profiles (users added through Connect)</font>';

$string['prefix']              	 = 'Prefix for User-IDs';
$string['prefix_hint']         	 = '<font size=\"1\">We suggest a 4-charachter code to ensure unique user IDs on Connect hosted accounts</font>';

$string['aclogin']             	 = 'Adobe Connect Login';

$string['cachetime']           	 = 'Connect Cache Timeout';
$string['cachetime_hint']      	 = '<font size=\"1\">Enter the number of minutes to keep cache info for meeting and content details<br />' .
                                   'from the Connect Server. Longer values will improve performance considerably but<br />' .
                                   'may result in a time lag when information is changed on the Connect Server.<br /></font>';

$string['mins']                  = ' minutes';
$string['hours']                 = ' hours';                                 
                                 
$string['emailaslogin']          = 'Force email as AC login';
$string['emailaslogin_hint']     = '<font size=\"1\">This must match the setting in your Connect Account</font>';

$string['updatelogin']           = 'Update AC login when email';
$string['updatelogin_hint']      = '<font size=\"1\">When Force email as AC login is on, update login when email is changed.</font>';

$string['unameaslogin']          = 'Force username as AC login';
$string['unameaslogin_hint']     = '<font size=\"1\">This will be superseded by email as login, if it is set.</font>';

$string['telephony']             = 'Meeting Dial-in Information';
$string['telephony_hint']        = '<font size=\"1\">Disable this option (No) if you prefer students to not see the dial-in information on the course page.</font>';

$string['mouseovers']            = 'Mouseovers for students';
$string['mouseovers_hint']       = '<font size=\"1\">Disable this option (No) if you prefer students to not have a box to display upon moving the mouse over the icon.</font>';

$string['oldplayer']             = 'Use Flash Player';
$string['oldplayer_hint']        = '<font size=\"1\">This setting when checked will disable the JW Player for flash videos and enable the old flash player.</font>';

$string['videoserver']           = 'Full path to Flash Videos';
$string['videoserver_hint']      = '<font size=\"1\">This value will be pre-pended to Flash video filenames that are not fully qualified</font>';

$string['videophoto']            = 'Default photo image';
$string['videophoto_hint']       = '<font size=\"1\">The Flash video player will display the image defined above<br />' .
                                         'unless a still image name is given</font>';

$string['videophotopath']        = 'Full path to Photo Images';
$string['videophotopath_hint']   = '<font size=\"1\">The Flash video player will prepend the path defined above<br />unless the still image name is fully qualified</font>';

$string['nosso']                 = 'Turn off automatic updating of Adobe Users and SSO connecting.';
$string['nosso_hint']            = '<font size=\"1\">This disables the link between the LMS and Adobe for users.  Users will need to be logged into Adobe independently.</font>';

$string['execsingle']            = 'Turn off multi-statement processing.';
$string['execsingle_hint']       = '<font size=\"1\">This disables multi-statement calls to Adobe used for efficiency.  It will slow the system down to use this, however, some sites cannot handle this.</font>';

$string['popup_height']          = 'Popup window height.';
$string['popup_height_hint']     = '<font size=\"1\">Height of the popup window launched for an Adobe meeting/presentation.  Determines the default size of a popup window in pixels.</font>';

$string['popup_width']          = 'Popup window width.';
$string['popup_width_hint']     = '<font size=\"1\">Width of the popup window launched for an Adobe meeting/presentation.  Determines the default size of a popup window in pixels.</font>';

$string['notfound']              = 'NO SUCH URL EXISTS';

$string['tollfree']              = 'Toll-Free:';
$string['pphone']                = 'Passcode:';

$string['launch_meeting']        = 'Click to enter the Meeting Room';
$string['launch_content']        = 'Click to view the Presentation';
$string['launch_archive']        = 'Click to view the Recording';

$string['launch_edit']           = 'Edit this Resource at Adobe Connect Central';

$string['views']                 = 'View(s) to date';
$string['editcal']               = 'Synchronize the Calendar Event for this Meeting';
$string['viewattendees']         = 'View Meeting Attendee List';
$string['mailattendees']         = 'Email Meeting Details';

$string['mailsubject']           = 'Re: $a->title';
$string['mailbody']              = 'Dear

Please join me in an on-line meeting entitled: <a href=\"$a->url\">$a->title (click to launch)</a>

The event will be held on: $a->date
Conference Information: $a->phone

Regards,

$a->from

$a->summary

Please Note this event can also be accessed directly at $a->cplongurl (log in as a guest)';

$string['attendees']      = 'Meeting Attendees';
$string['eventname']      = 'Meeting Date';
$string['login']          = 'Login Name';
$string['fullname']       = 'Full Name';
$string['minutes']        = 'Minutes in Meeting';
$string['entrytime']      = 'First Entry';
$string['mtgrole']        = 'Role';
$string['attendeecount']  = 'Users in Room';
$string['backtocourse']   = 'Return to Course';
$string['meeting']        = 'Meeting URL';
$string['minabb']         = 'minute';
$string['minsabb']        = 'minutes';
$string['mymeetings']     = 'My Upcoming Meetings';
$string['myrecordings']   = 'My Recordings';
$string['mtgenter']       = 'Click to Enter the Meeting Room: ';



///////////////////////////////////////////////////////////////////////////////////////////////
$string['refined_link_type']         = 'Connect Resource Type';
$string['refined_link_mtg']          = 'Meeting';
$string['refined_link_preso']        = 'Presentation';
$string['refined_link_video']        = 'Video';
$string['refined_link_recording']    = 'Recording';
$string['refined_link_other']        = 'Other';

$string['filtername']                = 'Connect';
$string['refineddescription']        = 'Configure your Adobe Connect Server Settings';

$string['refinedprotocol']           = 'Preferred Protocol';
$string['refinedprotocol_hint']      = '<font size=\"1\">Select how users should connect to your Connect content</font>';

$string['refineddomain']             = 'Connect Server Domain';
$string['refineddomain_hint']        = '<font size=\"1\">This is the domain name advertised to users</font>';

$string['refinedadmindomain']        = 'Connect Admin Domain';
$string['refinedadmindomain_hint']   = '<font size=\"1\">Domain name for API Calls - typically <b>admin.acrobat.com</b> for hosted accounts</font>';

$string['refinedaccount']            = 'Connect Account ID';
$string['refinedaccount_hint']       = '<font size=\"1\">Shows up in the URL at Connect Central on the Admin pages</font>';

$string['refinedcacheedit']          = 'Reset cache in edit mode';
$string['refinedcacheedit_hint']     = '<font size\"1\">When in edit mode, the cache will automatically be cleared unless this box is unchecked.</font>';

$string['refinedcachenow']           = 'Reset cache NOW';
$string['refinedcachenow_hint']      = '<font size\"1\">The next time the cache is checked, all contents will be cleared.  Using this allows ' .
                                       'you to set a long time to keep the cache, and when you change the contents on Connect, you can ' .
                                       'manually reset the cache.</font>';

$string['groupcommit']               = 'Group Commit Performance Enhancements';
$string['groupcommit_hint']          = '<font size\"1\">Turns on the group commit switch in every header and footer in the system.  By turning ' .
                                       'this on, logins to Connect Central will be buffered for multiple calls during one page load. For pages ' .
                                       'with multiple icons, performance will be greatly improved.';

$string['refinedadminname']          = 'Administrative Login';
$string['refinedadminname_hint']     = '<font size=\"1\">Ensure this user exists in your Connect account and has full admin rights</font>';

$string['refinedadminpassword']      = 'Administrative Password';
$string['refinedadminpassword_hint'] = '<font size=\"1\">The password for the above user name</font>';

$string['refinedguestuser']          = 'Guest User Name';
$string['refinedguestuser_hint']     = '<font size=\"1\">Ensure this user exists in your Connect account and has no rights at all</font>';

$string['refinedguestpassword']      = 'Guest Password';
$string['refinedguestpassword_hint'] = '<font size=\"1\">The password for the above user name</font>';

$string['refinedsurveyurl']          = 'Survey Gizmo URL';
$string['refinedsurveyurl_hint']     = '<font size=\"1\">Only required if you want pre-meeting surveys</font>';

$string['refinedtimeoffset']         = 'Time Adjustment Setting';
$string['refinedtimeoffset_hint']    = '<font size=\"1\">Number of seconds to add (use minus sign to subtract)<br />' .
                                     'to compensate for the time difference between your<br />' .
                                     'timezone and the server\'s timezone.<br />' .
                                     '(e.g. -3600 to account for daylight savings.)</font>';

$string['defaultstate']              = 'Default State/Province';
$string['configstate']               = 'If you set a State/Province here, then this value will be selected by default on new user accounts. To force users to choose a State/Province, just leave this unset.';
$string['refined_clock']             = 'Analog Clock';
$string['refined_clock_title']       = 'It\'s Always the Right Time to Learn at Refined Data';

$string['refined_acp_login']         = 'Connect Login';
$string['refined_link_mtg_min']      = 'Minimum Minutes in Meeting Required';
$string['refined_link_slide_min']    = 'Minimum Slide Views Required';

$string['refined_mtg_view_preso']    = 'Click to View the Presentation: ';
$string['refined_mtg_view_rec']      = 'Click to View the Recording: ';
$string['refined_mtg_view_file']     = 'Click to View the Resource: ';

$string['refined_guest_login']       = 'Meeting Login';
$string['refined_guest_fullname']    = 'Your Full Name';
$string['refined_guest_submit']      = 'Enter the Meeting Room';
$string['refined_guest_prompt']      = 'Please enter your full name (First and Last names) above to enter the meeting';
$string['refined_guest_error']       = 'You must type your full name to enter the meeting';

$string['refined_url_notfound']      = 'NO SUCH URL EXISTS';

$string['refined_mtg_launch_cpro']   = 'Launch Connect Central';

$string['refined_mtg_hour_abb']      = 'hr';
$string['refined_mtg_hours_abb']     = 'hrs';

$string['refined_assignment']        = '<center><font size=\"+1\"><font color=\"red\">Warning:</font><br />' .
                                       'This is an auto-generated assignment type created to track access to ' .
                                       'Adobe Connect Meetings,<br />Slide Views of Presenter Presentations ' .
                                       'and viewing of Flash Video movies.<br /><br />' .
                                       'Do not manually create assignments using this form.<br /><br />' .
                                       'This assignment should remain hidden from students and is used ' .
                                       'only for tracking purposes.<br /><br />' .
                                       'You can override the participation records for course users ' .
                                       'by manually editing their grade for this assignment.</font></center>';

$string['refined_autologin']         = 'Auto Login:';
$string['refined_autologin_note']    = '(Please replace ACPURL with your Connect custom URL in the above link.)';

$string['rs_expired_message']        = 'This activity module requires renewal by the system administrator.  Please contact them for any questions.';

$string['rtdocs']                    = 'RT User Guide';
$string['userdown']                  = 'Download Users';
$string['workbook']                  = 'Workbook';

$string['rtdocchp1']                 = 'Introduction';
$string['rtdocchp2']                 = 'Refined Tags';
$string['rtdocchp3']                 = 'Connect Activities';
$string['rtdocchp4']                 = 'Tutor Sessions';
$string['rtdocchp5']                 = 'Prerequisites ';
$string['rtdocchp6']                 = 'Event Reminders';
$string['rtdocchp7']                 = 'Webinar Enhancements';
$string['rtdocchp8']                 = 'Corporate Branding';
$string['rtdocchp9']                 = 'Job Functions';
$string['rtdocchp10']                = 'Access Tokens';
$string['rtdocchp11']                = 'Locations & Managers';
$string['rtdocchp12']                = 'Refined Reporting';
$string['rtdocchp13']                = 'Connect Filters';
$string['rtdocchp14']                = 'Intranet SSO';
$string['rtdocchp15']                = 'Security';

$string['viewlimit']                 = 'Number of viewings: ';

$string['viewpastsessions']          = 'Vantage Point Past Sessions';
?>
