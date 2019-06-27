<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_AuthorProfile
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: AuthorProfile.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Abstract
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_AuthorProfile
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_AuthorProfile extends Application_Article_View
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'article_name',  );

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			if( ! $data = $this->getIdentifierData() ){  }
			if( ! $data  
				|| ( ! @$data['publish'] && ! self::isOwner( $data['user_id'] ) )   
				|| ( ! $data['username'] )   
				|| ! self::hasPriviledge( $data['auth_level'] )
			)
			{
			//	var_export( $data );
				return false;
			//	self::setIdentifierData( $data );
			}
		//	var_export( $this->getParameter() );
			$parameters = 
				( 
					$this->getParameter() ? : array() 
				) 
				+ 
				array
				( 
						'markup_template_namespace' => __CLASS__ . 'organizations',
						'username_to_show' => $data['username'],
						'markup_template' => $this->getParameter( 'markup_template' ) ? : '<img style="float:left; max-height:45px; margin-bottom: 0.5em; margin-right: 0.5em;" src="{{{document_url}}}" alt="{{{article_description}}}" title="{{{article_description}}}"/><h3><a href="{{{article_url}}}">{{{article_title}}}</a></h3><p>{{{article_description}}}</p>{{{clear_float}}}', 
				);
		//	var_export( $parameters );
			$markup = Application_Article_ShowAll::viewInLine( $parameters );
			if( $this->getParameter( 'show_heading' ) )
			{
				$this->setViewContent( $this->getParameter( 'heading' ) ? : '<h2>About ' . $data['username'] . '</h2>' ); 
			}
			$this->setViewContent( $markup ); 
		}
		catch( Exception $e )
		{ 
			$this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( self::__( '<p class="badnews">Error with article package.</p>' ) ); 
		}
	//	var_export( $this->_xml );
    } 
	
	// END OF CLASS
}
