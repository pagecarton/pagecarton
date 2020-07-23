<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Pages_Duplicate
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Duplicate.php Friday 15th of September 2017 03:02PM  $
 */

/**
 * @see Ayoola_Page_Layout_Pages
 */

class Ayoola_Page_Layout_Pages_Duplicate extends Ayoola_Page_Layout_Pages
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
	protected static $_objectTitle = 'Duplicate a theme page'; 

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
			if( ! $data = $this->getIdentifierData() ){ ; }
		
		//	var_export( $this->getFilename() );
            $url = @$_REQUEST['url'];
            $themeName = strtolower( $data['layout_name'] ? : Application_Settings_Abstract::getSettings( 'Page', 'default_layout' ) );
            if( ! $themeName )
            {
                return false;
            }
        //    var_export( $data );
        //    var_export( $themeName );
           
            $allPages = self::getPages( $themeName, 'list' );
            $allPages = array_combine( $allPages, $allPages );
            if( ! in_array( $url, $allPages ) )
            {
           //     $this->setViewContent( self::__( '<p class="badnews">Page not found in theme.</p>' ) ); 
            //    return false;   
            }
            
            $form = new Ayoola_Form();
            $form->submitValue = 'Duplicate Page';
            $fieldset = new Ayoola_Form_Element();

            $fieldset->addElement( array( 'name' => 'old_page', 'label' => 'Copy', 'placeholder' => 'e.g. /new-page', 'type' => 'Select', 'value' => $url ), $allPages );
            $fieldset->addElement( array( 'name' => 'new_page', 'label' => 'To', 'placeholder' => 'e.g. /new-page', 'type' => 'InputText', 'value' => null ) );

            $form->addFieldset( $fieldset );
            $this->setForm( $form );
			$this->setViewContent( $form->view(), true);
			if( ! $values = $form->getValues() ){ return false; }
        //    var_export( $values );

            $values['new_page'] = '' . trim( preg_replace( '|[^a-zA-Z0-9]|', '-', $values['new_page'] ), '-/ ' );

        //    var_export( $values['new_page'] );
            $values['new_page'] = '/' . $values['new_page'];
            if( ! $values['new_page'] )
            {
                $this->setViewContent( self::__( '<p class="badnews">Invalid page name</p>' ) ); 
                return false;   
            }
        //    var_export( $values );

       //     return;
            if( $values['old_page'] === '/' )
            {
                $values['old_page'] = '/index';
            }
            if( $values['new_page'] === '/' )
            {
                $values['new_page'] = '/index';
            }
            $from = 'documents/layout/' . $themeName . '' . $values['old_page'] . '.html';
        //    var_export( $values );
        //    var_export( $from );
        //     $all = Ayoola_Loader::getValidIncludePaths( $from );
        //    var_export( $all );


            if( ! $from = Ayoola_Loader::getFullPath( $from, array( 'prioritize_my_copy' => true ) ) )
            {
                $this->setViewContent( self::__( '<p class="badnews">Page not found in theme.</p>' ) ); 
                return false;   
            }
            $to = Ayoola_Doc_Browser::getDocumentsDirectory() . '/layout/' . $themeName . '' . $values['new_page'] . '.html';

            if( is_file( $to ) )
            {
                $this->setViewContent( self::__( '<p class="badnews">Page already exist.</p>' ) ); 
                return false;   
            }

       //     ;
        //    var_export( $from );
        //    var_export( $to );

            if( copy( $from, $to ) )
            {
                $this->setViewContent(  '' . self::__( '<p class="goodnews">"' . $values['new_page'] . '" theme page created successfully.</p>' ) . '', true  ); 
                $fPaths = array();
                $tPaths = array();
                $fPaths = static::getPagePaths( $themeName, $values['old_page'] );
                $tPaths = static::getPagePaths( $themeName, $values['new_page'] );
                foreach( $fPaths as $key => $each )
                {
                    if( $from = Ayoola_Loader::getFullPath( $each, array( 'prioritize_my_copy' => true ) ) )
                    {
                        $to = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . $tPaths[$key];
                        Ayoola_Doc::createDirectory( dirname( $to ) );
                        copy( $from, $to );
                    }
                }
            }
            else
            {
                $this->setViewContent( self::__( '<p class="badnews">Theme Page could not be duplicated.</p>' ) ); 
            }

             // end of widget process
          
		}  
		catch( Exception $e )
        { 
            //  Alert! Clear the all other content and display whats below.
            $this->setViewContent(  '' . self::__( 'Theres an error in the code' ) . '', true  ); 
            return false; 
        }
	}
	// END OF CLASS
}
