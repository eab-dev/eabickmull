{* Display authors for Ickmull info file. Included by info.tpl *}

{if $node.data_map.author.content.author_list|count|gt(1)}

Authors:
{foreach $node.data_map.author.content.author_list as $author}

{$author.name} {if $author.email}<{$author.email}>{/if}
{/foreach}
{elseif $node.data_map.author.content.author_list|count|eq(1)}
Author: {$node.data_map.author.content.author_list.0.name} {if $node.data_map.author.content.author_list.0.email}<{$node.data_map.author.content.author_list.0.email}>{/if}
{/if}
