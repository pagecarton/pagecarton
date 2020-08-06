<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_User_Abstract
 */
 
require_once 'Application/User/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_ShowAll extends Application_User_Abstract
{
	
    /**
     * Access level for player
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
		$this->setViewContent(  '' . self::__( '' ) . '', true  );
		
		//	Choose the kind of users to show
		switch( $this->getParameter( 'profile_type' ) )
		{
			case 'same':
				if( ! empty( Ayoola_Application::$GLOBAL['user']['access_level'] ) )
				{
					$this->_dbWhereClause['access_level'] = Ayoola_Application::$GLOBAL['user']['access_level'];   
				}
			break;
			case 'string':
			default:
			break;
		}
		if( $authLevel = $this->getParameter( 'auth_level' ) )
		{
			switch( gettype($authLevel ) )
			{
				case 'array':
				//	Do nothing
				break;
				case 'string':
					$authLevel = array_map( 'trim', explode( ',', $authLevel ) );
				default:
				break;
			}
//			var_export( $authLevel );
			$this->_dbWhereClause['access_level'] = $authLevel;
		}
		
		//	switch templates off
	//	$this->_parameter['markup_template'] = null;
		$data = $this->getDbData();
		krsort( $data );
		if( $data )
		//while( $this->getDbData() )
		{
		//	$data = array_shift( $this->getDbData() );
			$dataToPad = array( 
							'article_url' => '',   
						//	'article_url' => '/' . $data['username'],   
							'allow_raw_data' => true, 
							'article_type' => 'user_information', 
							'document_url' => '',
						//	'document_url' => 'http://placehold.it/300x300&text=No Picture',
							'publish' => true, 
							'auth_level' => 0, 
							'article_title' => '', 
							'article_description' => '', 
						);
		//	self::v( $data );
			//	Use article viewer to bring out this
			$parameters = array_merge( $this->getParameter() ? : array(), array( 'data_to_merge' => $dataToPad, 'get_access_information' => true ) );
			$class = new Application_Article_ShowAll( array( 'no_init' => true ) );
			$class->setDbData( $data );
			$class->setParameter( $parameters );
			$class->init();
			$this->setViewContent( $class->view() );
		//	self::v( $class->getParameter( 'markup_template' ) );
			$this->_parameter['markup_template'] = $class->getParameter( 'markup_template' );
			$this->_parameter['markup_template_suffix'] = null;
			$this->_parameter['markup_template_prefix'] = null;
			$this->_objectTemplateValues = $class->getObjectTemplateValues();
			//	break;
		}
	//	$this->setViewContent(  '' . self::__( '' ) . '', true  );
    } 
		// END OF CLASS
}
