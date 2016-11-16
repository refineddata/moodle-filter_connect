<?php // $Id: view.php,v 1.95.2.6 2007/11/26 14:43:07 dwoolhead Exp $

    require_once('../../config.php');
    require_login();
    $PAGE->set_url('/filter/connect/transcripts.php');
    $PAGE->set_pagelayout( 'admin' );
    $context = context_course::instance(SITEID );
    $PAGE->set_context( $context );
    $strtitle = 'Transcripts';
    $PAGE->set_title( $strtitle );
    $PAGE->set_heading( $strtitle );
	
    global $CFG, $USER, $DB, $OUTPUT;
    
    $userid = optional_param( 'id',   0, PARAM_INT );
    $down   = optional_param( 'down', 0, PARAM_INT );
    
    if ( !isset( $userid ) OR !$userid ) $userid = $USER->id;
    $user = $DB->get_record( 'user', array('id'=>$userid) );

    if ( isset( $down ) AND $down ) _download( $user );
    
    // Print title and header
    if ( !$site = get_site() ) redirect( $CFG->wwwroot . '/' . $CFG->admin . '/index.php' );
    $pagetitle     = '';
    $strtranscript = "Transcript"; //get_string( 'transcript', 'connectpro' );
    $strcourse     = get_string( 'name' );
    $strtype       = get_string( 'certificatetype', 'certificate' );
    $strstatus     = get_string( 'status' );
    $strscore      = get_string( 'grade', 'local_connect' );
    $strcert       = get_string( 'modulename', 'certificate' );
    $strdate       = get_string( 'date' );
    //$navlinks      = array();
    //$navlinks[]    = array( 'name'=>$strtranscript, 'link'=>'', 'type'=>'misc' );
    //$navlinks[]    = array( 'name'=>fullname( $user ), 'link'=>'', 'type'=>'misc' );
    $PAGE->navbar->add( $strtranscript, '' );
    $PAGE->navbar->add( fullname( $user ), '' );

    //$navigation    = build_navigation( $navlinks );
	
    echo $OUTPUT->header( "$site->shortname: $strtranscript : " . fullname( $user ), $strtranscript, '' );
    echo $OUTPUT->heading( $strtranscript . ' : ' . fullname( $user ) );

	
	// ----
	// Transcripts
	// ----
	// use the connect.lib to retrieve the adobe connect transcripts, and grab previously stored transcripts? (perhaps update our DB)
    if ( isset( $user->transcript ) AND $user->transcript ) {
		$transcripts = $DB->get_records_sql( "SELECT * FROM {$CFG->prefix}local_transcripts WHERE login = '$user->aclogin'" );
	} else {
        // get Historical Records from adobe
        //require_once( $CFG->dirroot.'/lib/connect/core.php' );
        //require_once( $CFG->dirroot.'/lib/connect/user.php' );
        $transcripts = connect_get_transcript( $user->id );
        if ( isset( $user->transcript ) ) {
            foreach( $transcripts as $trans ) {
                $DB->insert_record( 'local_transcripts', $trans );
            }
            $DB->set_field( 'user', 'transcript', 1, array('id'=>$user->id) );
            $user->transcript = 1;
        }
    }

	// ----
	// Build Table
	// ----
    $table = new html_table();
    $table->head  = array( $strcourse, $strtype, $strstatus, $strscore, $strcert, $strdate );
    $table->align = array( "left", "left", "left", "left", "left", "left" );
    $table->width = "100%";


    if ( !empty( $transcripts ) ) {
	
		// ----
		// Find Transcripts
		// ----
        foreach( $transcripts as $item ) {
            if ( $item->status == 'completed' OR $item->status == 'user-passed' ) {
                $table->data[] = array( $item->course, $item->type, $item->status, $item->score, $item->cert, DATE( 'Y-m-d', $item->date ) );
            }
        }
		
		// ----
		// Write Table
		// ----
		if ( !empty( $table ) ) echo html_writer::table( $table );

		if ( !empty( $table->data ) ) {
			echo '<br />';
			global $SESSION;
			$SESSION->transtable   = $table;
			echo '<center><a href="'. $CFG->wwwroot . '/filter/connect/transcripts.php?down=1&id=' . $userid . '"><input type="button" value="' . get_string( 'download' ) . '" /></a></center>';
			echo '<br/>';
		}
	
    }
	
	

    
	if ( count($transcripts) == 0 ) {
		echo '<p>This user has no transcripts to report</p>';
	}
	
    echo $OUTPUT->footer();
    
function _download( $user ) {
    $name = fullname( $user );
    
    global $SESSION, $CFG;
    $table = $SESSION->transtable;
    
    // Setup Excel
    require_once( $CFG->libdir.'/pear/Spreadsheet/Excel/Writer.php' );
    $workbook = new Spreadsheet_Excel_Writer();
    $fbold    = &$workbook->addFormat();
    $fbold->setBold();
    $fbold->setTextWrap();
    $fbold->setVAlign('top');

    $workbook->send( $name . '.xls' );
    $ws_reg =& $workbook->addWorksheet( 'Transcripts' );
    $ws_reg->setColumn( 0, 0, 50 );
    $ws_reg->setColumn( 1, 5, 10 );
    $row    = 0;
    $col    = 0;

    foreach( $table->head as $column ) {
        $ws_reg->write( $row, $col++, $column, $fbold );
    }
    
    foreach( $table->data as $data ) {
        $row++;
        $col = 0;
        foreach( $data as $item ) {
            $ws_reg->write( $row, $col++, strip_tags( $item ) );
        }
    }
    $workbook->close();
    die;
}
?>