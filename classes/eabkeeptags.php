<?php

class eabKeepTags
{
    function eabKeepTags()
    {
    }

    function operatorList()
    {
        return array( 'keeptags' );
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array( 'keeptags' => array( 'tags' => array( 'type' => 'array',
                                                            'required' => false,
                                                            'default' => array('<p>') ) ) );
    }

    function modify( $tpl, $operatorName, $operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
        switch ( $operatorName )
        {
            case 'keeptags':
            {
                $tags = implode( '', $namedParameters['tags'] );
                $operatorValue = strip_tags( $operatorValue, $tags );
            } break;
        }
    }
}

?>
