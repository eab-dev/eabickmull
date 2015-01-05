{def $can_download_current_class = false()}

{foreach $policies as $policy}
    {if and( eq( $policy.moduleName, 'ickmull' ), eq( $policy.functionName, 'download' ), is_array( $policy.limitation ) )}
        {if $policy.limitation[0].values_as_array|contains( $content_object.content_class.id )}
            {set $can_download_current_class = true()}
            {break}
        {/if}
        {elseif or( and( eq( $policy.moduleName, '*' ), eq( $policy.functionName, '*' ), eq( $policy.limitation, '*' ) ),
                    and( eq( $policy.moduleName, 'ickmull' ), eq( $policy.functionName, '*' ), eq( $policy.limitation, '*' ) ),
                    and( eq( $policy.moduleName, 'ickmull' ), eq( $policy.functionName, 'download' ), eq( $policy.limitation, '*' ) ) )}
            {set $can_download_current_class = true()}
            {break}
        {/if}
{/foreach}

{if $can_download_current_class}
<div id="ezwt-icml-zip">
    <a href={concat( "/ickmull/zip/", $current_node_id )|ezurl} title="{'Download as ICML ZIP'|i18n('design/standard/parts/website_toolbar')}"><img src={"download.png"|ezimage} width="16" height="16" alt="{'Download'|i18n('design/standard/parts/website_toolbar')}"/></a>
</div>
{/if}
