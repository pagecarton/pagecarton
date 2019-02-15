<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Default
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Default.php 5.15.12 8.49 ayoola $
 */

/**
 * @see Ayoola_Doc_Adapter_Abstract_Octet
 */
 
require_once 'Ayoola/Doc/Adapter/Abstract/Octet.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Doc_Adapter_Default
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Doc_Adapter_Default extends Ayoola_Doc_Adapter_Abstract_Octet
{
    /**
     * Allowed extensions for this adapter
     *
     * @var array
     */
	protected static $_allowedExtentions = array( 
													'ttf' => 'application/x-font-ttf', 
													'woff' => 'application/font-woff', 
													'woff2' => 'application/font-woff', 
													'eot' => 'application/vnd.ms-fontobject', 
													'svg' => 'image/svg+xml', 
													'otf' => 'application/x-font-opentype', 
													'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',  
													'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',  
												);
	
	
    /**
     * Return _allowedExtentions
     *
     * @return array
     */
    public static function getAllowedExtentions()
    {
        return self::$_allowedExtentions;
    } 
	// END OF CLASS
}
