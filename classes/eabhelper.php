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

class Helper {

    /**
    * Generate a single CSV record from an array of values
    *
    * @param array fields
    * @param string delimiter
    * @param string enclosure for strings
    * @param boolean should all fields be enclosed?
    * @return string single CSV record
    */
    function arrayToCSV( array $fields, $delimiter = ',', $enclosure = '"', $encloseAll = false )
    {
        $delimiter_esc = preg_quote( $delimiter, '/' );
        $enclosure_esc = preg_quote( $enclosure, '/' );

        $outputString = "";
        $output = array();
        foreach ( $fields as $field ) {
            // Enclose fields containing $delimiter, $enclosure or whitespace
            if ( $encloseAll || preg_match( "/(?:${delimiter_esc}|${enclosure_esc}|\s)/", $field ) ) {
                $output[] = $enclosure . str_replace($enclosure, $enclosure . $enclosure, $field) . $enclosure;
            }
            else {
                $output[] = $field;
            }
        }
        $outputString .= implode( $delimiter, $output )."\r\n";
        return $outputString;
    }
    
}

