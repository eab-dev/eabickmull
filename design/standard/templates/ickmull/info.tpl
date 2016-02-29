This ZIP archive contains text content in Adobe InDesign ICML format and any associated images.

Name: {$node.name}
Downloaded from: {$node.url_alias|ezurl('no','full')}
Downloaded on: {currentdate()|datetime( 'custom', '%d %F %Y %H:%i' )}

Last modified on: {$node.object.modified|datetime( 'custom', '%d %F %Y %H:%i' )}
Modified by: {$node.creator.name}

Created on: {$node.object.published|datetime( 'custom', '%d %F %Y %H:%i' )}
Created by: {$node.object.owner.name}



Contains {$image_count} image{if $image_count|gt(1)}s{/if}:

{if ezini( 'Archive', 'ListImagesInInfo', 'ickmull.ini' )|eq( "enabled" )}

{foreach $image_list as $image}
{include uri="design:ickmull/image.tpl" image=$image}
{/foreach}
{/if}

{if and( ezini( 'Archive', 'ListImagesInCSV', 'ickmull.ini' )|eq( "enabled" ), $image_count|gt(0) )}

See {$image_file}.

{/if}
