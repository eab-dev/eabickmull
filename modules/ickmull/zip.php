<?php

/*
 * Copyright (C) 2015 Enterprise AB Ltd http://eab.uk
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

if ( isset( $Params[ 'NodeID' ])) {
    $nodeID = $Params[ 'NodeID' ];
    $node = eZContentObjectTreeNode::fetch( $nodeID );
    if ( !$node ) {
        die( "Invalid NodeID parameter" );
    }
}
else {
    die( "Expected NodeID parameter" );
}

$object = $node->object();

$dataMap = $object->dataMap();

$relatedObjects = eZContentFunctionCollection::fetchRelatedObjects(
                        $object->ID,
                        true, // attributeID
                        true, // array( 'common', 'xml_embed', 'attribute', 'xml_link' )
                        false, // groupByAttribute can be true or false
                        false, // sortBy array
                        false, // limit
                        false, // offset
                        true, // asObject = true or false
                        false, // loadDataMap = true or false
                        null, // $ignoreVisibility = null
                        array( 'image' ) // $relatedClassIdentifiers = null
                    );

$zip = new \EAB\Ickmull\IckmullZip( $object );
$tpl = eZTemplate::factory();
$tpl->setVariable( 'datamap', $dataMap );
$xhtml = $tpl->fetch( 'design:ickmull/xhtml.tpl' );

$zip->addICML( $xhtml );

// Add the article image (if it has one)
$zip->addImage( $object );

// Add images embedded in the XML (if any)
foreach ( $relatedObjects as $relationType ) {
    foreach ( $relationType as $related ) {
        $zip->addImage( $related );
    }
}

$zip->addInfo( $node );
$zip->complete();
$zip->output();

eZExecution::cleanExit();
