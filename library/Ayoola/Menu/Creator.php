<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Menu_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $ 
 */

/**
 * @see Ayoola_Menu_Abstract
 */
 
require_once 'Ayoola/Menu/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Menu_Creator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
class Ayoola_Menu_Creator extends Ayoola_Menu_Abstract
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
			$this->createForm( 'Continue..', 'Create a menu template' );
			$this->setViewContent( $this->getForm()->view() );
		//	self::v( $_POST );
			if( ! $values = $this->getForm()->getValues() ){ return false; } 
			if( $this->insertDb( $values ) )
			{ 
				$this->setViewContent( '<span class="boxednews normalnews centerednews">Menu template created successfully. </span>', true ); 
			}
			
		}
		catch( Exception $e )
		{ 
			$this->_parameter['markup_template'] = null;
			$this->setViewContent( '<p class="blockednews badnews centerednews">' . $e->getMessage() . '</p>', true );
		//	return $this->setViewContent( '<p class="blockednews badnews centerednews">Error with article package.</p>' ); 
		}
    } 
}
