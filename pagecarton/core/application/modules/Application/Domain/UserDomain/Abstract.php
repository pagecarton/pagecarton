<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Domain_UserDomain_Abstract
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Friday 6th of July 2018 07:44AM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */


class Application_Domain_UserDomain_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'userdomain_id' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'userdomain_id';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Domain_UserDomain';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 1 );


    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;

		$fieldset = new Ayoola_Form_Element;
	//	$fieldset->placeholderInPlaceOfLabel = false;    
        if( ! empty( $_REQUEST['external_domain'] ) )   
        {
            $fieldset->addElement( array( 'name' => 'domain_name', 'label' => 'Domain Name', 'placeholder' => 'e.g. example.com', 'type' => 'InputText', 'value' => @$values['domain_name'] ) ); 
        }
        else
        {
            $table = "Application_Domain_Order";
            $table = $table::getInstance();
            $domains = $table->select( null, array( 'username' => Ayoola_Application::getUserInfo( 'username' ) ) );
			$filter = new Ayoola_Filter_SelectListArray( 'domain_name', 'domain_name' );
            $domains = $filter->filter( $domains );
            
            $fieldset->addElement( array( 'name' => 'domain_name', 'label' => 'Domain', 'placeholder' => 'e.g. example.com', 'type' => 'Select', 'onchange' => 'if( this.value == \'__custom\' ){ var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'value' => @$values['domain_name'] ), array( '' => 'Select Domain' ) + $domains + array( '__custom' => 'Custom Domain' ) );   
        }
        $fieldset->addRequirement( 'domain_name', array( 'NotEmpty' => null, 'DuplicateRecord' => array( 'Application_Domain_UserDomain', 'domain_name' ) ) ); 
   //   $fieldset->addElement( array( 'name' => 'user_id', 'type' => 'InputText', 'value' => @$values['user_id'] ) ); 
   //   $fieldset->addElement( array( 'name' => 'username', 'type' => 'InputText', 'value' => @$values['username'] ) ); 

		if( ! self::hasPriviledge( 98 ) )
		{
			$profiles = Application_Profile_Abstract::getMyProfiles();
			if( ! empty( $values['profile_url'] ) && ! in_array( $values['profile_url'], $profiles ) )
			{
				$profiles[] = $values['profile_url'];
			}
			$profiles = array_map( 'strtolower', $profiles );
			$profiles = array_combine( $profiles, $profiles );
		}
		else
		{
			$profiles = Application_Profile_Table::getInstance()->select();
			$filter = new Ayoola_Filter_SelectListArray( 'profile_url', 'profile_url' );
			$profiles = $filter->filter( $profiles );
		}
        Application_Javascript::addCode
        (
            '
                ayoola.addShowProfileUrl = function( target )
                {
                    var element = document.getElementById( "element_to_show_profile_url" );
                    element = element ? element : document.createElement( "div" );
                    element.id = "element_to_show_profile_url";
                    var a = false;
                    if( target.value )
                    {
                        a = true;
                    }
                    if( a )
                    {
                        element.innerHTML = "<span class=\'\' style=\'font-size:x-small\'>The domain name will display contents of: <a target=\'_blank\' href=\'http://" + target.value + ".' . Ayoola_Application::getDomainName() . '\'>http://" + target.value + ".' . Ayoola_Application::getDomainName() . '</a></span>";
                    }  
                    target.parentNode.insertBefore( element, target.nextSibling );
                }
            '
        );
		$fieldset->addElement( array( 'name' => 'profile_url', 'onfocus' => 'ayoola.addShowProfileUrl( this );', 'onchange' => 'ayoola.addShowProfileUrl( this );', 'label' => 'Site', 'type' => 'Select', 'value' => @$values['profile_url'] ? : Application_Profile_Abstract::getMyDefaultProfile() ), array( '' => 'Select Profile' ) + $profiles );
        $fieldset->addRequirement( 'profile_url', array( 'ArrayKeys' => $profiles ) ); 

		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 

	// END OF CLASS
}
