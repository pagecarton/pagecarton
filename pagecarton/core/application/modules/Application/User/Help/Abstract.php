<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Application_User_Help_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_User_Help_Exception 
 */
 
require_once 'Application/User/Help/Exception.php';


/**
 * @user   Ayoola
 * @package    Application_User_Help_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_User_Help_Abstract extends Application_User_Abstract
{
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue, $legend = null, Array $values = null )
    {
		require_once 'Ayoola/Form.php';
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$form->submitValue = $submitValue ;
	//	$form->setSubmitButton( $submitValue );
		//$form->setCaptcha( true ); // Adds captcha
		require_once 'Ayoola/Form/Element.php';
/*		$personal = new Ayoola_Form_Element;
		$personal->addElement( array( 'name' => 'firstname', 'description' => 'Your given name', 'type' => 'InputText', 'value' => @$values['firstname'] ) );
		$personal->addRequirement( 'firstname','Name:: WordCount=>2;;20' );
		$personal->addElement( array( 'name' => 'lastname', 'description' => 'Your family name', 'type' => 'InputText', 'value' => @$values['lastname'] ) );
		$personal->addRequirement( 'lastname','Name:: WordCount=>2;;20' );
		$option = array( 'M' => 'Male', 'F' => 'Female' );
		$personal->addElement( array( 'name' => 'sex', 'description' => 'Please select', 'type' => 'Select', 'value' => @$values['sex'] ), $option );
		$personal->addRequirement( 'sex','InArray=>' . implode( ';;', array_keys( $option ) ) . ':: WordCount=>1;;1' );
	//	$personal->addElement( array( 'name' => 'birth_date', 'label' => 'Date of Birth', 'description' => 'Valid Format is YYYY-MM-DD', 'type' => 'InputText', 'value' => @$values['birth_date'] ) );
		
		//	retrieve birthday
		@list( $values['birth_year'], $values['birth_month'], $values['birth_day'] ) = explode( '-', $values['birth_date'] );
	//		self::v( $previousBirthdays );
		
		//	Month
		$options = array_combine( range( 1, 12 ), array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ) );
		$birthMonthValue = intval( @strlen( $values['birth_month'] ) === 1 ? ( '0' . @$values['birth_month'] ) : @$values['birth_month'] );
		$birthMonthValue = intval( $birthMonthValue ?  : $this->getGlobalValue( 'birth_month' ) );
	//	var_export( $birthMonthValue );
	//	var_export( $this->getGlobalValue( 'birth_month' ) );
		$personal->addElement( array( 'name' => 'birth_month', 'label' => 'Date of Birth', 'style' => 'min-width:10%;max-width:25%;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthMonthValue ), array( 'Month' ) + $options ); 
		$personal->addRequirement( 'birth_month', array( 'InArray' => array_keys( $options ) ) );
		if( strlen( $this->getGlobalValue( 'birth_month' ) ) === 1 )
		{
			$personal->addFilter( 'birth_month', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'birth_month' ) ) );
		}
		
		//	Day
		$options = range( 1, 31 );
		$options = array_combine( $options, $options );
		$birthDayValue = intval( @strlen( $values['birth_day'] ) === 1 ? ( '0' . @$values['birth_day'] ) : @$values['birth_day'] );
		$birthDayValue = intval( $birthDayValue ?  : $this->getGlobalValue( 'birth_day' ) );
		$personal->addElement( array( 'name' => 'birth_day', 'label' => '', 'style' => 'min-width:10%;max-width:25%;display:inline-block;;margin-right:0;', 'type' => 'Select', 'value' => $birthDayValue ), array( 'Day' ) +$options );
		$personal->addRequirement( 'birth_day', array( 'InArray' => array_keys( $options ) ) );
		if( strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 )
		{
			$personal->addFilter( 'birth_day', array( 'DefiniteValue' => '0' . $this->getGlobalValue( 'birth_day' ) ) );
		}
		
		//	Year
		//	Age must start from 13 yrs
		$options = range( date( 'Y' ) - 13, 1900 );
		$options = array_combine( $options, $options );
		$personal->addElement( array( 'name' => 'birth_year', 'label' => '', 'style' => 'min-width:10%;max-width:25%;display:inline-block;margin-right:0;', 'type' => 'Select', 'value' => @$values['birth_year'] ), array( 'Year' ) + $options );
		$personal->addRequirement( 'birth_year', array( 'InArray' => array_keys( $options ) ) );
		
		//	Birthday combined
		$personal->addElement( array( 'name' => 'birth_date', 'label' => 'Date of Birth', 'placeholder' => 'YYYY-MM-DD', 'type' => 'Hidden', 'value' => @$values['birth_date'] ) );
	//	$personal->addRequirement( 'birth_date','Date=>YYYYMMDD' );
		$dob = $this->getGlobalValue( 'birth_year' ) . '-';
		$dob .= ( strlen( $this->getGlobalValue( 'birth_month' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_month' ) ) : $this->getGlobalValue( 'birth_month' ) ) . '-';
		$dob .= strlen( $this->getGlobalValue( 'birth_day' ) ) === 1 ? ( '0' . $this->getGlobalValue( 'birth_day' ) ) : $this->getGlobalValue( 'birth_day' );
		$personal->addFilter( 'birth_date', array( 'DefiniteValue' => $dob ) );
		$personal->addRequirement( 'birth_date','Date=>YYYY-MM-DD' );
		$personal->addFilters( 'Trim::Escape' );
		$personal->addLegend( "Personal Information" );
*/		$account = new Ayoola_Form_Element;
	//	$account->addElement( array( 'name' => 'username', 'description' => 'You can leave blank if you can\'t remember it', 'type' => 'InputText', 'value' => @$values['username'] ) );
	//	$account->addFilter( 'username','Username' );
		$account->addElement( array( 'name' => 'email', 'placeholder' => 'e.g. me@example.com', 'type' => 'InputText', 'value' => @$values['email'] ) );
		$account->addRequirement( 'email','EmailAddress' );
	//	$account->addLegend( "Account Information" );
		$account->addFilters( 'Trim::Escape' );
		$form->addFieldset( $account );
	//	$form->addFieldset( $personal );
		return $this->setForm( $form );
    } 
	// END OF CLASS
}
