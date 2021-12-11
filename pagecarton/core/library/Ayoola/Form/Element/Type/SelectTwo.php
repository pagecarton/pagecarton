<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Form_Element_Type_SelectTwo
 * @copyright  Copyright (c) 2020 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SelectTwo.php Friday 7th of August 2020 02:02PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Form_Element_Type_SelectTwo extends PageCarton_Widget
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 0 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Select2 Form Element'; 

    /**
     * Performs the whole widget running process
     * 
     */
	public function init()
    {    
		try
		{ 
            $valueX = array();
            foreach( $this->getParameter( 'values' ) as $key => $value )
            {
                $selected = null;
                if( $value === $this->getParameter( 'value' ) || 
                    ( is_array( $this->getParameter( 'value' ) ) && in_array( $value, $this->getParameter( 'value' ) ) )
                )
                {
                    $selected = 'selected';
                }
                $valueX[] = array( 'key' => $key, 'value' => $value, 'selected' => $selected );
            }
            Application_Style::addFile( 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css' );
            Application_Javascript::addFile( 'https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js' );

            $config = $this->getParameter( 'config' );
            if( ! is_string( $config ) )
            {
                if( ! empty( $config['ajax']['url'] ) && Ayoola_Application::getUrlPrefix() && stripos( $config['ajax']['url'], Ayoola_Application::getUrlPrefix() ) !== 0 )
                {
                    $config['ajax']['url'] = Ayoola_Application::getUrlPrefix() . $config['ajax']['url'];
                }
                $config = json_encode( $config );
            }
            $this->_parameter['id'] = $this->getParameter( 'id' ) ? : 'select2-x';

            Application_Javascript::addCode( '
                $(document).ready(function() {
                    $(".' . $this->getParameter( 'id' ) . '").select2( ' . $config . ' );
                });
            ' 
            );
            $this->_objectTemplateValues = $this->getParameter();
            $this->_objectTemplateValues['values'] = $valueX;
            if( ! empty( $this->_objectTemplateValues['multiple'] ) )
            {
                $this->_objectTemplateValues['multiple'] = 'multiple';
            }
        }  
		catch( Exception $e )
        { 
            $this->setViewContent( self::__( '<p class="badnews">Theres an error in the code</p>' ) ); 
            return false; 
        }
	}
	// END OF CLASS
}
