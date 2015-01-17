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

namespace EAB\Ickmull;

class KeepTags
{
    function KeepTags()
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
