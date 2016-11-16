<?php // $Id: view.php,v 1.95.2.6 2007/11/26 14:43:07 dwoolhead Exp $
    require_once( '../../config.php' );
    require_once( 'lib.php' );
    global $CFG, $DB, $OUTPUT;

    $acurl  = optional_param( 'acurl', '', PARAM_RAW );  // URL to Adobe Resource
    $course = optional_param( 'course', 0, PARAM_INT );

    if ( empty( $acurl ) ) error( 'Must provide an Adobe Custom URL.' );
    
    require_login( $course );

    $stratt = get_string( 'attendees', 'filter_connect' ); 
    $button = $OUTPUT->single_button( new moodle_url( '/course/view.php', array( 'id' => $course ) ), get_string( 'backtocourse', 'filter_connect' ) );

    // Print title and header
    $PAGE->set_url( '/filter/connect/attendees.php' );
    $PAGE->set_pagelayout( 'standard' );
    $PAGE->set_title( $stratt . ': ' . $acurl );
    $PAGE->set_heading( $stratt );
    $PAGE->set_button( $button );

    echo $OUTPUT->header();
    echo connect_attendance_output( $acurl );
    echo $OUTPUT->footer();
?>