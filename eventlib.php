<?php
/**
 */
require_once($CFG->dirroot .'/mod/connectmeeting/connectlib.php');

function user_created_event( $event ) {
	global $DB, $CFG;
	
	$data = $event->get_data();
	$user = $DB->get_record( 'user', array('id' => $data['objectid'] ));
	
    if ( isset( $CFG->connect_nosso ) AND $CFG->connect_nosso ) return true;
    if ( ! connect_update_user( $user ) ) return false;
    if (isset($user->postcode)) unset($user->postcode);
    if (isset($user->state)) unset($user->state);    
    $DB->update_record( 'user', $user );
    return true;
}

function user_updated_event( $event ) {
    global $DB, $CFG;
    
    $data = $event->get_data();
    $user = $DB->get_record( 'user', array('id' => $data['objectid'] ));
    
    if ( isset( $CFG->connect_nosso ) AND $CFG->connect_nosso ) return true;
    if ( ! connect_update_user( $user ) ) return false;
    $DB->update_record( 'user', $user );
    return true;
}

function user_deleted_event( $event ) {
    global $DB, $CFG;
    
    $data = $event->get_data();
    
    if ( ! connect_remove_user( $data['objectid'] ) ) return false;
    return true;
}

function password_changed_event( $event ) {
    global $DB, $CFG;
    
    $data = $event->get_data();
    $user = $DB->get_record( 'user', array('id' => $data['objectid'] ));
    
    if ( isset( $CFG->connect_nosso ) AND $CFG->connect_nosso ) return true;
    if ( ! connect_update_user( $user ) ) return false;
    $DB->update_record( 'user', $user );
    return true;
}

function course_created_event( $event ) {
	global $DB;
	
	$data = $event->get_data();
	
    if ( ! connect_update_group( $data['other']['shortname'], $data['contextinstanceid'] ) ) return false;
    return true;
}

function course_updated_event( $event ) {
	global $DB;
	
	$data = $event->get_data();
	
    if ( ! connect_update_group( $data['other']['shortname'], $data['contextinstanceid'] ) ) return false;
    return true;
}

function course_deleted_event( $event ) {
	global $DB;
	
	$data = $event->get_data();
	$course = $DB->get_record( 'course', array('id' => $data['contextinstanceid'] ));
	
    if ( ! connect_remove_group( $data['contextinstanceid'] ) ) return false;
    return true;
}

function role_assigned_event( $event ) {
    global $DB, $CFG;
    
    $data = $event->get_data();
    
    $user    = $DB->get_record( 'user', array( 'id'=>$data['relateduserid'] ) );
    $context = $DB->get_record( 'context', array( 'id'=>$data['contextid'] ) );
    $currentcontext = context::instance_by_id($data['contextid']);
    
    if ( ! $course  = $DB->get_record( 'course', array( 'id'=>$context->instanceid ) ) ) return true;
    connect_group_access( $user->id, $course->id, true );
    
    if( has_capability( 'mod/connectmeeting:host', $currentcontext, $user ) || has_capability( 'mod/connectmeeting:presenter', $currentcontext, $user ) ){
        $type = has_capability( 'mod/connectmeeting:host', $currentcontext, $user ) ? 'host' : 'mini-host'; // if they have both, then they take the higher permission level, host

        $connects = $DB->get_records( 'connectmeeting', array( 'course' => $course->id, 'type' => 'meeting' ) );
        foreach( $connects as $connect ){
            connect_add_access( $connect->id, $user->id, 'user', $type );
        }
    }

    return true;
}

function role_unassigned_event( $event ) {
    global $DB, $CFG;
    
    $data = $event->get_data();

    $user    = $DB->get_record( 'user', array( 'id'=>$data['relateduserid'] ) );
    $context = $DB->get_record( 'context', array( 'id'=>$data['contextid'] ) );
    $currentcontext = context::instance_by_id($data['contextid']);

    if( ! $course  = $DB->get_record( 'course', array( 'id'=>$context->instanceid ) ) ) return true;
    connect_group_access( $user->id, $course->id, false );
    
    $role = $DB->get_record_sql( "SELECT * FROM {role_capabilities} WHERE roleid = ? AND ( capability = ? OR capability = ? )", array( $data['objectid'], 'mod/connect:host', 'mod/connect:presenter' ), IGNORE_MULTIPLE );
    if( $role ){
        // the role they are having unassigned had host or presenter, so we need to adjust there permissions in AC for this courses meetings
        $type = 'remove';
        
        $connects = $DB->get_records( 'connectmeeting', array( 'course' => $course->id, 'type' => 'meeting' ) );
        foreach( $connects as $connect ){
            connect_add_access( $connect->id, $user->id, 'user', $type );
        }
    }

    return true;
}

?>
