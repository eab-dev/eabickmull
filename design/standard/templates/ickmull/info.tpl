This ZIP archive contains text content in Adobe InDesign ICML format and any associated images.

Name: {$node.name}
Downloaded from: {$node.url_alias|ezurl('no','full')}
Downloaded on: {currentdate()|datetime( 'custom', '%d %F %Y %H:%i' )}

Last modified on: {$node.object.modified|datetime( 'custom', '%d %F %Y %H:%i' )}
Modified by: {$node.creator.name}

Created on: {$node.object.published|datetime( 'custom', '%d %F %Y %H:%i' )}
Created by: {$node.object.owner.name}



{if $node.data_map.author.content.author_list|count|gt(0)}

Author{if $node.data_map.author.content.author_list|count|gt(1)}s{/if}:
{foreach $node.data_map.author.content.author_list as $author}

{$author.name} {if $author.email}<{$author.email}>{/if}
{/foreach}

{/if}


{if $image_count|gt(0)}

Contains {$image_count} image{if $image_count|gt(1)}s{/if}: see {$image_file}.


