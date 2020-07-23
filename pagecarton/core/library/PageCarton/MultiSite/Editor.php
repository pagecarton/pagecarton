<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    PageCarton_MultiSite_Editor
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_MultiSite_Editor extends PageCarton_MultiSite_Abstract
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Edit a Site'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$link = '' . Ayoola_Page::getRootUrl() . '' . $data['directory'];
			$this->createForm( 'Save', 'Edit settings of this site', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
            
			if( $this->updateDb() ){ $this->setViewContent(  '' . self::__( '<div class="goodnews">Site information saved successfully</div>' ) . '', true  ); } 
            
            if( ! $data['redirect_url'] && trim( $values['redirect_url'] ) )
            {
                if( ! self::deleteFiles( $data['directory'] ) )
                {

                }     
            }
            elseif( trim( $data['redirect_url'] ) && ! trim( $values['redirect_url'] ) )
            {
                if( ! self::copyFiles( $data['directory'] ) )
                {

                }     
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( '<p class="badnews">Theres an error in the code</p>' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
