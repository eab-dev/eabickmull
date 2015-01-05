<?php

set_time_limit(0);
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array(
    'description' => "Add the ickmull download policy to the 'Editor' role.\n\n",
    'use-modules' => true,
    'use-extensions' => true
));

$script->startup();
$script->initialize();

// get the role we want change by name
$role = eZRole::fetchByName( 'Editor' );

// to add a policy we pass the module, the function and the limitation array
$class = eZContentClass::fetchByIdentifier( 'article' );
$role->appendPolicy( "ickmull", "download", array( "Class" => $class->ID ));

$script->shutdown();

?>
