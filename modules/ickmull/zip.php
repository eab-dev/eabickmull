<?php

//$Module =& $Params['Module'];
//$ini = eZINI::instance();
//$http = eZHTTPTool::instance();

print_r( $Params );
echo "<hr/>";
if ( isset( $Params[ 'NodeID' ]))
{
	$nodeID = $Params[ 'NodeID' ];
	$node = eZContentObjectTreeNode::fetch( $nodeID );
	if ( !$node )
		die("Invalid NodeID parameter");
}
else
	die("Expected NodeID parameter");

$object = $node->object();

/* $relatedObjects = eZContentFunctionCollection::fetchRelatedObjects 	( 	  	$objectID,
		  	$attributeID,
		  	$allRelations,
		  	$groupByAttribute,
		  	$sortBy,
		  	$ignoreVisibility = null 
	);
*/

print_r( $node );
echo "<hr/>";

$zip = new IckmullZip( $object );
$xhtml = "This will be translated into ICML";
$zip->addICML( $xhtml );
$zip->output();

echo "<hr/>";

eZExecution::cleanExit();

?>
