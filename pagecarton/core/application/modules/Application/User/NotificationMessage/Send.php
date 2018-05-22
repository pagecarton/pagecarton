<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Send.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_NotificationMessage_Abstract
 */
 
require_once 'Application/User/NotificationMessage/Abstract.php';


/**
 * @user   Ayoola
 * @package    Application_User_NotificationMessage_Send
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_User_NotificationMessage_Send extends Application_User_NotificationMessage_Abstract
{
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = self::getIdentifierData() ){ return false; }
			$modeClass = __CLASS__ . '_';
			$interface = $modeClass . 'Interface';
			$modeClass .= $data['mode_name'];
			if( ( ! $path = Ayoola_Loader::loadClass( $modeClass ) ) ||(  ! new $modeClass instanceof $interface ) )
			{ 
				throw new Application_User_NotificationMessage_Exception( 'Driver not found for notification mode - ' . $data['mode_name'] ); 
			}
			$this->createForm( 'Send',  'Send ' . $data['subject'], $data ); 
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			$values = array_merge( $data, $values );
			
			$modeClass = new $modeClass;
		//	$recipients = trim( $values['to'] . ',' . $values['cc'] . ',' . $values['bcc'], ',' );
			$modeClass->sendMessage( $values );
			$user_id = 'user_id';
		//	var_export( $modeClass );
			
			$count = count( $values['auth_level'] );
			$where = null;
			$i = 0;
			if( ! $database = Application_Settings_Abstract::getSettings( 'UserAccount', 'default-database' ) )
			{
				$database = 'cloud';
			}
			$saved = false;
			$message = null;
			$select = array();
			switch( $database )
			{
				case 'cloud':
					$response = Ayoola_Api_UserList::send( array( 'access_level' => $values['auth_level'] ) );
			//		var_export( $response ); 
		//			var_export( $values );
					if( is_array( @$response['data'] ) )
					{
						$select = $response['data'];
					}
					else
					{
						$this->getForm()->setBadnews( $response );
					}
					
				break;
				case 'relational':
					foreach( $values['auth_level'] as $each )
					{
						$i++;
						$where .= " access_level = '{$each}' "; 
						$where .= $i == $count ? null : "OR";
					}
					$table = new Application_User; 
					$select = array();
					if( $where )
					{
						$select = $table->select( '', 'useremail, usersettings, userpersonalinfo', $where );
					}
				break;
			}
		//	var_export( $values );
		//	var_export( $where );
		//	var_export( $select );
			foreach( $select as $selectValue )
			{
				$eachValues = $values;
				$search = array();
				$replace = array();
/* 			//	$pattern = "/@@@(\w+)@@@/e";
			//	$replacement = '$selectValue["\\1"]';
 				foreach( $selectValue as $key => $value )
				{
					$search[] = '@@@' . $key . '@@@';
					$replace[] = $value;
				}
 			//	@$eachValues['subject'] = preg_replace( $pattern, $replacement, $eachValues['subject'] );
 			//	@$eachValues['body'] = preg_replace( $pattern, $replacement, $eachValues['body'] );
				$eachValues['subject'] = str_ireplace( $search, $replace, $eachValues['subject'] );
				$eachValues['body'] = str_ireplace( $search, $replace, $eachValues['body'] );
 */				
				
				$eachValues = Application_User_Abstract::replacePlaceholders( $eachValues, $selectValue );
				$requirements = array();
				$list = null;
			//	var_export( $eachValues['body'] );
				foreach( $modeClass->getRequiredTables() as $column => $table )
				{
			//		$table = new $table;
			//		$requirements = $table->select( $column, '', " {$user_id} = '{$selectValue[$user_id]}' " );
					//	var_export( $requirements );
				//	foreach( $requirements as $requirementValue )
					{
						$list .= $selectValue[$column] . ',';
					}
					$eachValues['to'] = $list;
				//	var_export( $eachValues );
					$modeClass->sendMessage( $eachValues );
			//		var_export( $eachValues['body'] );
				}
				
				//	Wait a few more seconds before you send another to prevent spam.
				sleep( 10 );
			}
			$this->setViewContent( '<span class="goodnews boxednews centerednews">Message sent to ' . count( $select ) . ' users.</span>', true );
		}
		catch( Exception $e ){ return false; }
						//var_export( $list );
	}
		
    /**
     * creates the form for creating and editing
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
    //    $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'enctype' => 'multipart/form-data' ) );
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'cc', 'description' => 'Copy this recievers', 'type' => 'InputText', 'value' => @$values['cc'] ) );
		$fieldset->addElement( array( 'name' => 'bcc', 'description' => 'Also send to to this recievers', 'type' => 'InputText', 'value' => @$values['bcc'] ) );
		
		$authLevel = new Ayoola_Access_AuthLevel;
		$authLevel = $authLevel->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'auth_level', 'auth_name' );
		$authLevel = $filter->filter( $authLevel );
		$fieldset->addElement( array( 'name' => 'auth_level', 'description' => 'Select the user-levels to send message', 'type' => 'Checkbox', 'value' => @$values['auth_level'] ), $authLevel );
		$fieldset->addRequirement( 'auth_level', array( 'InArray' => array_keys( $authLevel )  ) );
		unset( $authLevel );
		$fieldset->addElement( array( 'name' => __CLASS__, 'value' => $submitValue, 'type' => 'Submit' ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );
    } 
	// END OF CLASS 
}
