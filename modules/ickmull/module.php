<?php

$Module = array( 'name' => 'EAB Ickmull' );

$ViewList = array(
    'zip' => array(
        'script' => 'zip.php',
        'params' => array( 'NodeID' ),
        'functions' => array( 'download' )
    )
);

$FunctionList = array(
    'download'=> array(
        'Class' => array(
            'name'=> 'Class',
            'values'=> array(),
            'class' => 'eZContentClass',
            'function' => 'fetchList',
            'parameter' => array( 0, false, false, array( 'name' => 'asc' ) ) 
        )
    )
);

?>
