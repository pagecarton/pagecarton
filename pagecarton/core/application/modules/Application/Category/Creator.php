<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Category_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Creator.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Category_Abstract
 */
 
require_once 'Application/Category/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Category_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Category_Creator extends Application_Category_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Continue', 'Create a Category' );
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() ){ return false; }
		
		if( @$_REQUEST['parent_category_name'] && ! @$values['parent_category_name'] )
		{
			$values['parent_category_name'] = $_REQUEST['parent_category_name']; 
		}

		$values['category_name'] = @$values['category_name'] ? : $values['category_label'];

		$values['category_name'] = self::filterCategoryName( $values['category_name'] );
		if( ! $this->insertDb( $values ) ){ return $this->setViewContent( $this->getForm()->view(), true ); }
		$this->setViewContent(  '' . self::__( '<div><p class="goodnews">Category created successfully.<p></div>' ) . '', true  );

	} 
	// END OF CLASS
}
