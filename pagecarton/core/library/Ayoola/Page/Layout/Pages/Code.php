<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Pages_Code
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Code.php Friday 15th of September 2017 03:02PM  $
 */

/**
 * @see Ayoola_Page_Layout_Pages
 */

class Ayoola_Page_Layout_Pages_Code extends Ayoola_Page_Layout_Pages
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Code theme page'; 

    /**
     * 
     * 
     * @var Ayoola_Page_Page 
     */
	protected static $_table; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            //  Code that runs the widget goes here...

            //  Output demo content to screen
			if( ! $data = $this->getIdentifierData() ){ return false; }
		
		//	var_export( $this->getFilename() );
            $url = @$_REQUEST['url'];
            
            $allPages = self::getPages( $data['layout_name'], 'list' );
            $allPages = array_combine( $allPages, $allPages );
            if( ! in_array( $url, $allPages ) )
            {
                $this->setViewContent( '<p class="badnews">Page not found in theme.</p>' ); 
                return false;   
            }
            $realUrl = $url;
            if( $realUrl === '/' )
            {
                $realUrl = '/index';
            }
            $from = 'documents/layout/' . $data['layout_name'] . '' . $realUrl . '.html';
        //    var_export( $from );
            if( ! $from = Ayoola_Loader::getFullPath( $from, array( 'prioritize_my_copy' => true ) ) )
            {
                $this->setViewContent( '<p class="badnews">Page not found in theme.</p>' ); 
                return false;   
            }
            $code = file_get_contents( $from );
            $form = new Ayoola_Form();
            $form->submitValue = 'Save Code';
            $fieldset = new Ayoola_Form_Element();

            $fieldset->addElement( array( 'name' => 'code', 'label' => 'Code for "' . $url . '"', 'placeholder' => 'HTML codes here...', 'type' => 'TextArea', 'value' => $code ) );

            $form->addFieldset( $fieldset );
			$this->setViewContent( $form->view(), true);
			if( ! $values = $form->getValues() ){ return false; }

            if( $values['code'] )
            {
                if( file_put_contents( $from, $values['code'] ) )
                {
                    $this->setViewContent( '<p class="goodnews">Code saved successfully.</p>', true ); 
                }
            }
             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent( 'Theres an error in the code', true ); 
            return false; 
        }
	}
	// END OF CLASS
}
