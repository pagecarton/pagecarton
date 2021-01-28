<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Video
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Video.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Video
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Video extends Application_Article_Type_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
 		Application_Style::addCode
		( 
			'
			.videoWrapper 
			{
				position: relative;
				padding-bottom: 56.25%; /* 16:9 */
				padding-top: 25px;
				height: 0;
			}
			.videoWrapper object,
			.videoWrapper embed,  
			.videoWrapper iframe  
			{
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
			' 
		);

			if( ! $data = $this->getParameter( 'data' ) )
			{
				$data = $this->getIdentifierData();
			}
		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
        if( stripos( $data['video_url'], 'youtu.be' ) !== false )
        {
            $data['video_url'] = 'https://youtube.com/embed/' . array_pop( explode( '/', $data['video_url'] ) );
        }
        elseif( stripos( $data['video_url'], '?v=' ) !== false )
        {
            var_export( $x );
            $x = array_pop( explode( '?v=', $data['video_url'] ) );
            var_export( $x );
            $x = array_shift( explode( '&', $x ) );
            var_export( $x );

            $data['video_url'] = 'https://youtube.com/embed/' . $x;
        }
		$this->setViewContent( self::__( '<div class="videoWrapper"><iframe width="' . ( @$articleSettings['cover_photo_width'] ? : '900' ) . '" height="' . ( @$articleSettings['cover_photo_height'] ? : '300' ) . '" src="' . $data['video_url'] . '" frameborder="0" allowfullscreen></iframe></div>' ) );
    } 
	
	
	
	// END OF CLASS
}
