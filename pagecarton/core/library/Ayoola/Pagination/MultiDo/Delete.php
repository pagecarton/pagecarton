<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Pagination_MultiDo_Delete
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php Saturday 16th of June 2018 03:03PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Pagination_MultiDo_Delete extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 98, 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Delete Multiple Items'; 

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
        //    var_export( $_REQUEST );
            $class = @$_GET['list_name'];
            if( ! Ayoola_Object_Embed::isWidget( $class, false ) )
            {
                $this->setViewContent( '<div class="badnews">Items does not support multiple delete.</div>', true );
                return false;
            }
            if( ! empty( $_GET['delete_class'] ) )
            {
                $deleteClass = $_GET['delete_class'];
            }
            else
            {
                $classNameArray = explode( '_', $class );
                if( array_pop( $classNameArray ) != 'List' )
                {
                    $this->setViewContent( '<div class="badnews">Items does not support multiple delete.</div>', true );
                    return false;
                }
                $deleteClass = implode( '_', $classNameArray ) . '_Delete';
            }
            if( ! Ayoola_Object_Embed::isWidget( $deleteClass, false) )
            {
                $this->setViewContent( '<div class="badnews">Items does not support multiple delete.</div>', true );
                return false;
            }  

            $class = new $deleteClass;
            $classId = $class->getIdColumn();

      //      var_export( $classId );
            if( empty( $_GET[$classId] ) || ! is_array( $_GET[$classId] ) )
            {
  //          var_export( $_GET[$classId] );
                $this->setViewContent( '<div class="badnews">You have not selected any items</div>', true );
                return false;
            }
            $recordIds = $_GET[$classId];
            $this->createConfirmationForm( 'Delete',  'Delete ' . count( $recordIds ) . ' item(s)' );
            $this->setViewContent( $this->getForm()->view(), true );
            if( ! $values = $this->getForm()->getValues() ){ return false; }
            $this->setViewContent( '<div></div>', true );
            set_time_limit( 0 );
            foreach( $recordIds as $each )
            {
                $identifier = array( $classId => $each );
                $class->setIdentifier( $identifier + ( $_GET ? : array() ) );
                $class->setParameter( $identifier );
                $class->setIdentifierData();
                $class->fakeValues = $identifier;
                $class->init();
                $this->setViewContent( $class->view() );
            //    ;
            }
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
