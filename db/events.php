<?php
/**
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
if (empty($CFG->connect_nosso) OR !$CFG->connect_nosso) {
    //Depreciated since Moodle 2.7
//    $handlers = array(
//        'user_created' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'user_created_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'user_updated' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'user_updated_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'user_deleted' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'user_deleted_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'password_changed' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'password_changed_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'role_assigned' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'role_assigned_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'role_unassigned' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'role_unassigned_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'course_created' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'course_created_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'course_updated' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'course_updated_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        ),
//
//        'course_deleted' => array(
//        'handlerfile'     => '/filter/connect/eventlib.php',
//        'handlerfunction' => 'course_deleted_event',
//        'schedule'        => 'instant',
//        'internal'        => 1
//        )
//    );
    $observers = array(
        array(
            'eventname' => '\core\event\user_created',
            'includefile' => '/filter/connect/eventlib.php',
            'callback' => 'user_created_event',
            'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\user_updated',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'user_updated_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\user_deleted',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'user_deleted_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\role_assigned',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'role_assigned_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\role_unassigned',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'role_unassigned_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\course_created',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'course_created_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\course_updated',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'course_updated_event',
        	'internal' => true,
        ),
        array(
        	'eventname' => '\core\event\course_deleted',
        	'includefile' => '/filter/connect/eventlib.php',
        	'callback' => 'course_deleted_event',
        	'internal' => true,
        ),
    );
} else {
	//Depreciated since Moodle 2.7
//     $handlers = array(

//         'role_assigned' => array(
//             'handlerfile' => '/filter/connect/eventlib.php',
//             'handlerfunction' => 'role_assigned_event',
//             'schedule' => 'instant',
//             'internal' => 1
//         ),

//         'role_unassigned' => array(
//             'handlerfile' => '/filter/connect/eventlib.php',
//             'handlerfunction' => 'role_unassigned_event',
//             'schedule' => 'instant',
//             'internal' => 1
//         ),

//         'course_created' => array(
//             'handlerfile' => '/filter/connect/eventlib.php',
//             'handlerfunction' => 'course_created_event',
//             'schedule' => 'instant',
//             'internal' => 1
//         ),

//         'course_updated' => array(
//             'handlerfile' => '/filter/connect/eventlib.php',
//             'handlerfunction' => 'course_updated_event',
//             'schedule' => 'instant',
//             'internal' => 1
//         ),

//         'course_deleted' => array(
//             'handlerfile' => '/filter/connect/eventlib.php',
//             'handlerfunction' => 'course_deleted_event',
//             'schedule' => 'instant',
//             'internal' => 1
//         )
//     );
    $observers = array(
    		array(
    			'eventname' => '\core\event\role_assigned',
    			'includefile' => '/filter/connect/eventlib.php',
    			'callback' => 'role_assigned_event',
    			'internal' => true,
    		),
    		array(
    			'eventname' => '\core\event\role_unassigned',
    			'includefile' => '/filter/connect/eventlib.php',
    			'callback' => 'role_unassigned_event',
    			'internal' => true,
    		),
    		array(
    			'eventname' => '\core\event\course_created',
    			'includefile' => '/filter/connect/eventlib.php',
    			'callback' => 'course_created_event',
    			'internal' => true,
    		),
    		array(
    			'eventname' => '\core\event\course_updated',
    			'includefile' => '/filter/connect/eventlib.php',
    			'callback' => 'course_updated_event',
    			'internal' => true,
    		),
    		array(
    			'eventname' => '\core\event\course_deleted',
    			'includefile' => '/filter/connect/eventlib.php',
    			'callback' => 'course_deleted_event',
    			'internal' => true,
    		),
    );
}

?>