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

use DOMDocument;
use eZINI;
use SimpleXMLElement;
use XSLTProcessor;
use ZipArchive;

class IckmullZip
{
    private $zip;
    private $filePath;
    private $safeName;
    private $imageList;

    const IMAGELISTFILE = "images.csv";
    const INFOFILE = "info.txt";

    public function __construct( $object )
    {
        // test if ZIP support is available in PHP
        if ( !in_array( 'zip', get_loaded_extensions() )) {
            die( '<h1>No ZIP support in PHP</h1>' );
        }

        $this->zip = new ZipArchive();
        $this->zip->setArchiveComment ( "Generated by EABIckmull, ICML generator for eZ Publish. Copyright (C) EAB Ltd 2015. http://eab.uk" );

        $this->safeName = \eZURLAliasML::convertToAlias( $object->Name );
        $storageDir = \eZSys::storageDirectory();

        $this->imageList = array();
        $this->filePath = $storageDir . "/ickmull_" . $object->ID . "_" . $this->safeName . ".zip";

        if ( $this->zip->open( $this->filePath, ZipArchive::CREATE ) !== true ) {
            if ( $this->zip->open( $this->filePath, ZipArchive::OVERWRITE ) !== true ) {
                die( "Cannot open file \"$this->filePath\"");
            }
        }
    }

    public function __destruct()
    {
        if ( in_array( 'zip', get_loaded_extensions() )) {
            $this->zip->close();
        }
    }

    private function appendImageDetails( $filename, $caption = "", $altText = "", $object = null )
    {
        $this->imageList[] = array(
                                    'filename' =>$filename,
                                    'caption' => $caption,
                                    'alt_text' => $altText,
                                    'object' => $object
                                );
    }

    private function addImageListCSV( $filename = self::IMAGELISTFILE )
    {
        if (count( $this->imageList ) > 0 ) {
            $csv = "\"Image Filename\",\"Caption Text\",\"Alt Text\"\n";
            foreach ( $this->imageList as $record ) {
                $csv .= Helper::arrayToCSV( $record );
            }
            $this->zip->addFromString( $filename, $csv );
        }
    }

    /**
    * Add a file INFOFILE containing some metadata about the content
    *
    * @param $xhtml
    * @return void
    */
    public function addInfo( $node )
    {
        $tpl = \eZTemplate::factory();
        $tpl->setVariable( 'node', $node );
        $tpl->setVariable( 'image_count', count( $this->imageList ) );
        $tpl->setVariable( 'image_list', $this->imageList );
        $tpl->setVariable( 'image_file', self::IMAGELISTFILE );
        $info = str_replace("\n", "\r\n", $tpl->fetch( 'design:ickmull/info.tpl' ) );
        $this->zip->addFromString( self::INFOFILE, $info );
    }

    /**
    * Generate ICML from the supplied XHTML and add it to the ZIP file
    *
    * @param $xhtml
    * @return void
    */
    public function addICML( $xhtml )
    {
        $xslDoc = new DOMDocument();
        $xslDoc->load( "extension/eabickmull/design/standard/stylesheets/tkbr2icml-v044.xsl" );

        $processor = new XSLTProcessor();
        $processor->importStylesheet( $xslDoc);
        $icml = $processor->transformToXml( new SimpleXMLElement( $xhtml ) );

        $this->zip->addFromString( $this->safeName . ".icml", $icml );
    }

    /**
    * Add original image from content object to the ZIP file
    * The name of the attribute containing the image is assumed to be 'image'
    * The name of the attribute containing the caption is assumed to be 'caption'
    * This may need to be changed later
    *
    * @param $object
    * @return void
    */
    public function addImage( $object )
    {
        $dataMap = $object->dataMap();

        $imageHandler = new ImageAliasHandler( $dataMap[ 'image' ] );

        if ( $imageHandler->attribute( 'is_valid' ) ) {

            $filepath = $imageHandler->attributeFromOriginal( 'url' );
            $filename = \eZURLAliasML::convertToAlias( $imageHandler->uniqueFilename() );

            $this->zip->addFile( $filepath, $filename );

            $caption = $dataMap[ 'caption' ];
            if ( $caption instanceof \eZContentObjectAttribute ) {
                $converter = new \eZXHTMLXMLOutput( $caption->DataText, false, $caption);
                $captionText = $converter->outputText();
            }
            else {
                $captionText = "";
            }

            $altText = $imageHandler->attribute( 'alternative_text' );

            $this->appendImageDetails( $filename, $captionText, $altText, $object );
        }
    }

    /**
    * Complete and close the ZIP file
    *
    * @param void
    * @return void
    */
    public function complete()
    {
        $ini = eZINI::instance( 'ickmull.ini' );
        if ( $ini->variable( 'Archive', 'ListImagesInCSV' ) == "enabled" ) {
            $this->addImageListCSV();
        }
        $this->zip->close();
    }

    /**
    * Download the ZIP file
    *
    * @param void
    * @return void
    */
    public function output()
    {
        ob_clean();

        header( "Pragma: " ); // Fixes problems with IE when opening a file directly
        header( "Cache-Control: " );

        // Last-Modified header cannot be set, otherwise browser like FF will fail while resuming a paused download
        // because it compares the value of Last-Modified headers between requests.
        header( "Last-Modified: " );

        // Set cache time out to 10 minutes, this should be good enough to work around an IE bug
        header( "Expires: " . gmdate( 'D, d M Y H:i:s', time() + 600 ) . " GMT" );

        header( "Content-Type: application/zip" );
        header( "Content-Disposition: attachment; filename=\"" . $this->safeName . ".zip\"" );
        header( "Content-Length: " . filesize( $this->filePath ));
        header( "Content-Transfer-Encoding: binary" );
        header( "Accept-Ranges: bytes" );

        ob_end_clean();

        set_time_limit( 0 );
        $file = fopen( $this->filePath, "rb" );
        while( !feof( $file )) {
            print( fread($file, 1024*8 ) ); // output in 8KB chunks
            ob_flush();
            flush();
        }
        \eZExecution::cleanExit();
    }

}
