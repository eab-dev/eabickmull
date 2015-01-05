<?php

/**
* EABIckmull
*
* PHP version 5
*
* @category PHP
* @package EABIckmull
* @author Andy Caiger
* @copyright 2015 Enterprise AB Ltd
* @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License V2
* @link http://eab.uk
*/
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//

class IckmullZip
{
    private $zip;
    private $filePath;
    private $safeFileName;

    public function __construct( $object )
    {
        // test if ZIP support is available in PHP
        if ( !in_array( 'zip', get_loaded_extensions() ))
            die( '<h1>No ZIP support in PHP</h1>' );

        $this->zip = new ZipArchive();
        $this->safeFileName = eZURLAliasML::convertToAlias( $object->Name ) . ".zip";
        $storageDir = eZSys::storageDirectory();
        $this->filePath = $storageDir . "/ickmull_contentobjectid_" . $object->ContentObjectID . ".zip";

        if ( $this->zip->open( $this->filePath, ZipArchive::OVERWRITE /* ZipArchive::CREATE */ ) !== TRUE)
            die( "Cannot open file \"$filePath\"");
    }

    public function __destruct()
    {
        $this->zip->close();
    }

    /**
    * Generate ICML from the supplied XHTML and add it to the ZIP file
    *
    * @param $object
    * @return void
    */
    public function addICML( $xhtml )
    {
        $this->zip->addFile( "README.md", "fake.icml" );
    }

    /**
    * Add original image from content object to the ZIP file
    *
    * @param $object
    * @return void
    */
    public function addImage( $object )
    {
        $this->zip->addFile( "README.md", "fake.png" );
    }

    public function output()
    {
        $this->zip->close();
        $size = filesize( $this->filePath );

        ob_clean();

        // Fixes problems with IE when opening a file directly
        header( "Pragma: " );

        header( "Cache-Control: " );

        // Last-Modified header cannot be set, otherwise browser like FF will fail while resuming a paused download
        // because it compares the value of Last-Modified headers between requests.
        header( "Last-Modified: " );

        /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
        header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + 600 ) . " GMT" );

        header( "Content-Type: application/zip" );
	
        header( "Content-Disposition: attachment; filename=\"" . $this->safeFileName . "\"" );
        header( "Content-Length: " . $size );
        header( "Content-Transfer-Encoding: binary" );
        header( "Accept-Ranges: bytes" );
        ob_end_clean();

        set_time_limit( 0 );
        $file = fopen( $this->filePath, "rb" );
        while( !feof( $file ))
        {
            print( fread($file, 1024*8 ) ); // output in 8KB chunks
            ob_flush();
            flush();
        }
        eZExecution::cleanExit();
    }

    /**
    * Converts xhtml to pdf
    *
    * @param $xhtml
    * @return Binary pdf content or false if error
    */
    public function generateZip( $object )
    {

        if ($zip->open( $filePath, ZipArchive::CREATE ) === TRUE)
        {
            $zip->addFile( "README.md","InsideZIP_README.md" );
            echo "numfiles: " . $zip->numFiles . "<br/>";
            echo "status:" . $zip->status . "<br/>";
            $zip->close();      
        }
        else
            die( "Cannot open file \"$filePath\"");

        eZExecution::cleanExit();
    }

    /**
    * Flush ZIP content to browser
    *
    * @param $data
    * @param $pdf_file_name
    * @param $size
    * @param $mtime Not used
    * @param $expiry Not used
    * @return void
    */
    public function flushZip( $data, $zipfilePath = 'file', $size, $mtime= false, $expiry = false )
    {
        // wash filePath to prevent file donwload injection attacks
        $zipfilePath = self::wash( $zipfilePath );

        ob_clean();
        header( "X-Powered-By: eZ Publish - EABIckMull" );

        // Fixes problems with IE when opening a file directly
        header( "Pragma: " );

        header( "Cache-Control: " );

        // Last-Modified header cannot be set, otherwise browser like FF will fail while resuming a paused download
        // because it compares the value of Last-Modified headers between requests.
        header( "Last-Modified: " );

        /* Set cache time out to 10 minutes, this should be good enough to work around an IE bug */
        header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + 600 ) . " GMT" );

        header( "Content-Type: application/zip" );
        header( "Content-Disposition: attachment; filePath=\"" . $zipfilePath . "\"" );
        header( "Content-Length: " . $size );
        header( "Content-Transfer-Encoding: binary" );
        header( "Accept-Ranges: bytes" );
        ob_end_clean();
        echo $data;
        eZExecution::cleanExit();
    }

    /**
    * Removes any non-alphanumeric characters.
    *
    * @param String
    * @return String sanitized string
    */
    static function wash( $string )
    {
        return eZURLAliasML::convertToAlias( $string );
    }

}

?>