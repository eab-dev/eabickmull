<?php

/* 
 * Copyright (C) Enterprise AB Ltd http://eab.uk
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

class ImageAliasHandler extends \eZImageAliasHandler
{
   /**
    * Generate a unique filename based on original filename, attribute ID and version
    *
    * @param void
    * @return string
    */
    public function uniqueFilename()
    {
        $originalFilename = $this->attribute( 'original_filename' );
        $attributeData = $this->ContentObjectAttributeData['DataTypeCustom']['original_data'];
        return(
            pathinfo( $originalFilename, PATHINFO_FILENAME )
            . "-" . $attributeData[ 'attribute_id' ]
            . "-" . $attributeData[ 'attribute_version' ]
            . "." . pathinfo( $originalFilename, PATHINFO_EXTENSION )
        );
    }
}
