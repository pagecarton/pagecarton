<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Application_Slideshow_Template_Abstract
 */
 
//require_once 'Ayoola/Slideshow/Abstract.php';

  
/**
 * @category   PageCarton
 * @package    Application_Slideshow_Template_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Application_Slideshow_Template_Creator extends Application_Slideshow_Template_Abstract
{
		
    /**
     * Performs the creation process
     *
     * @param void
     * @return void
     */	
    public function init()
    {
		try
		{
			$this->createForm( 'Continue..', 'Create a slideshow template' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent(  '' . self::__( '<span class="boxednews normalnews centerednews">Slideshow template created successfully. </span>' ) . '', true  ); 
			}
			
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent(  '' . self::__( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>' ) . '', true  );
		//	return $this->setViewContent( self::__( '<p class="blockednews badnews centerednews">Error with article package.</p>' ) ); 
		}
    } 
}
