<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Sanitize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Sanitize.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
   
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Sanitize
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Sanitize extends Ayoola_Page_Editor_Layout
{
	
    /**
     * Switch whether to update layout on page load
     * 
     * @var boolean 
     */
	protected $_updateLayoutOnEveryLoad = true;

    /**
     *
     */
    public function init()
    {

		$this->createConfirmationForm( 'Sanitize Pages', 'Sanitize all page files and information' );
		$this->setViewContent( '<h3>NOTE:</h3>', true );		
		$this->setViewContent( '<p>This process will create a fresh copy of all the pages. A fresh copy of the layout template will be used in generating the new pages. A backup of the application is recommended.</p>' );		
		$this->setViewContent( '<a href="' . Ayoola_Application::getUrlPrefix() . '/ayoola/backup" class="pc-btn pc-btn-small">Backup Now!</a>' );
		$this->setViewContent( $this->getForm()->view() );
        if( ! $values = $this->getForm()->getValues() ){ return false; }
		if( $this->sanitize() ){ $this->setViewContent( 'Pages Sanitized Successfully', true ); }
    }
		
    /**
     * Performs the sanitization process
     *
     * @param void
     * @return boolean
     */	
    public function sanitize( $themeName = null ) 
    {
		ignore_user_abort();
		$pages = new Ayoola_Page();
		$where = array();		
		$defaultPages = array();		
		if( $themeName )
		{
			$where['layout_name'] = array( $themeName );
			if( strtolower( $themeName ) === strtolower( Ayoola_Page_Editor_Layout::getDefaultLayout() ) )
			{
				$where['layout_name'][] = '';
				
				//	autocreate some of the default pages
		//		$defaultPages = array( '/', '/object', '/account', '/accounts/signin', '/accounts/signup', '/404', '/blog', '/search', );		
		//		$defaultPages = array( '/tested', );		
			}
		}
		$pages = $pages->getDbTable()->select( null, $where );
		$pages = array_merge( $pages, $defaultPages );
	//	var_export( $defaultPages );
//		var_export( $themeName );
//		var_export( $pages );
//		exit();
//var_export( $where );
	//	var_export( $pages->getDbTable()->select( null, $where ) );    
		foreach( $pages as $page )    
		{
			$page = is_string( $page ) ? $page : $page['url'];
	//		set_time_limit( 30 );
		//	var_export( @$page['url'] ? : $page );
	//		var_export( $page );
	//		continue;
		//	$class = new Ayoola_Page_Editor_Layout();
		//	$class->setPageId( $page['page_id'] );
		//	if( $themeName && ( stripos( $page['url'], '/layout/' ) === 0 ) )
			if( stripos( $page, '/layout/' ) === 0 )
			{
				//	dont cause unfinite loop by updating theme when a theme is being sanitized
				continue;
			}
			$this->refresh( $page );   
		}
		return true;
    }
		
    /**
     * Performs the sanitization process
     *
     * @param void
     * @return boolean
     */	
    public function refresh( $page ) 
    {
		$this->setPageInfo( array( 'url' => $page ) );
	//	var_export( $page );
		$this->setPagePaths();
		$this->setValues();
		$this->_updateLayoutOnEveryLoad = true;
		parent::init(); // invoke the template update for this page.           
		return true;
    } 
	// END OF CLASS

}
