<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_SearchBox
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SearchBox.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_SearchBox
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SearchBox extends Ayoola_Abstract_Table
{
	/**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Search Box'; 
	
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
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
		//	$this->setViewContent( self::__( '<p></p>' ) );
			$term = htmlentities( strip_tags( $_GET['q'] ), ENT_QUOTES, "UTF-8", false );
			$html = '
						<form data-not-playable="true" style="padding:1em 0 1em 0;" method="get" action="' . ( $this->getParameter( 'action' ) ? : '' . Ayoola_Application::getUrlPrefix() . '/search' ) . '" class="">
						  <input style="width:80%;padding:1em;background-color:inherit; color:inherit;" name="q" type="search" value="' . $term . '" placeholder="' . htmlentities( $this->getParameter( 'placeholder' ) ? : 'What are you looking for?', ENT_QUOTES, "UTF-8", false ) . '"><button type="submit" style="width:20%;padding:1em;">Go</button>
						</form>
			';
    		$this->setViewContent( $html ); 
			if( $term )
			{
				$pageInfo = array(
					'title' => trim( $term . ' - ' .  Ayoola_Page::getCurrentPageInfo( 'title' ), '- ' )
				);
				Ayoola_Page::setCurrentPageInfo( $pageInfo );
				Application_SearchBox_Table::getInstance()->insert( array( 'keywords' => array_map( 'trim', explode( ' ', $term ) ), 'query' => $term, 'username' => Ayoola_Application::getUserInfo( 'username' ), 'user_id' => Ayoola_Application::getUserInfo( 'user_id' ) ) );  
			}
		}
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
