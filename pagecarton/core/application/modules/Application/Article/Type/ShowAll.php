<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowAll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_ShowAll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_ShowAll extends Application_Article_Type_TypeAbstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
	//		$articleSettings = Application_Article_Settings::getSettings( 'Articles' );
			$templateToUse = $this->getParameter( 'markup_template' ) ? : 
				'<p>{{{post_type}}}</p>

				<p><span style="font-size:12px;"><a href="{{{pc_url_prefix}}}/article/type/{{{post_type_id}}}">Browse All</a> :: <a href="{{{pc_url_prefix}}}/post/create?article_type={{{post_type_id}}}">Add new "{{{post_type}}}"</a> </span></p>

				<hr>';
			$template = null;
			
		//	$i = 0;
		//	var_export( $this->getDbData() );  
			foreach( $this->getDbData() as $postTypeInfo )
			{
				$template .= self::replacePlaceholders( $templateToUse, array( 'article_type' => $postTypeInfo['article_type'], 'post_type' => $postTypeInfo['post_type'], 'post_type_id' => $postTypeInfo['post_type_id'], 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
		//		$i++;
			}
			
			@$this->_parameter['markup_template'] = $template; 
		//	$this->setViewContent( $html );
		}
		catch( Application_Article_Exception $e )
		{ 
		//	$this->getForm()->setBadnews( $e->getMessage() );
			$this->setViewContent( $e->getMessage(), true );
			return false; 
		}
   } 
	// END OF CLASS
}
