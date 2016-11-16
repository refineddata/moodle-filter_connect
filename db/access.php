<?php // $Id: access.php,v 1.6.2.1 2008/07/24 21:58:08 skodak Exp $

$capabilities = array(
    'filter/connect:editresource' => array(
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'legacy' => array(
            'manager' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW
    ))
);
?>
