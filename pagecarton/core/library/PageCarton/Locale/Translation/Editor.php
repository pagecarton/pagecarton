<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_Locale_Translation_Editor
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Editor.php Wednesday 20th of December 2017 08:14PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_Locale_Translation_Editor extends PageCarton_Locale_Translation_Abstract
{

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            if( empty( $_REQUEST['translation_id'] ) && ! empty( $_REQUEST['originalstring_id'] ) && ! empty( $_REQUEST['locale_code'] ) )
            {
                $where = array( 'originalstring_id' => $_REQUEST['originalstring_id'], 'locale_code' => $_REQUEST['locale_code'] );
	    	    if( ! $translation = PageCarton_Locale_Translation::getInstance()->selectOne( null, $where ) )
                {
                    $translation = PageCarton_Locale_Translation::getInstance()->insert( $where );
                }
                $this->setIdentifier( $translation );
         //       var_export( $translation );
      //          var_export( $insert );

            }
            //  Code that runs the widget goes here...
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createForm( 'Save', 'Edit', $data );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }


			if( $this->updateDb( $values ) ){ $this->setViewContent( '<div class="goodnews">Data updated successfully</div>', true ); } 

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( '<p class="badnews">' . $e->getMessage() . '</p>' ); 
            $this->setViewContent( '<p class="badnews">Theres an error in the code</p>' ); 
            return false; 
        }
	}
	// END OF CLASS
}
