<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Database_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Database_Exception 
 */
 
require_once 'Application/Database/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Database_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Database_Abstract extends Ayoola_Abstract_Table
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
	protected static $_accessLevel = 99;
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'database_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Database';
	
    /**
     * 
     * 
     */
	public static function setDefaultDatabase( array $values )
    {
		if( $values['default'] )
		{ 
			$table = Application_Database::getInstance();
			$values = $table->selectOne( null, array( 'database' => $values['database'], 'username' => $values['username'], ) );
			$database = '<?php' . PHP_EOL . '$_DATABASE = ' . var_export( $values, true ) . ';'; 
			$filename = APPLICATION_PATH . DS . 'configs/database.php';
			Ayoola_Doc::createDirectory( dirname( $filename ) );
			Ayoola_File::putContents( $filename, $database );
		}
	}
    /**
     * creates the form
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
	//	var_export( Ayoola_Application::getUserAccountInfo() );
	
		//	
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		
		$fieldset->addElement( array( 'name' => 'database', 'label' => 'Database Name', 'description' => 'Will become "' . Ayoola_Application::getUserAccountInfo( 'userid' ) . '_databaseName"', 'type' => 'InputText', 'value' => @$values['database'] ) );
		
		$options = new Application_Database_Account;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'username', 'username');
		$options = $filter->filter( $options );
		$fieldset->addElement( array( 'name' => 'username', 'label' => 'Choose an account to connect with', 'type' => 'Radio', 'value' => @$values['username'] ), $options );
		$fieldset->addRequirement( 'username', array( 'InArray' => array_keys( $options )  ) );
		unset( $options );
		
		$options = array( 1 => 'Yes' );
		$fieldset->addElement( array( 'name' => 'default', 'label' => 'Mark as default', 'type' => 'Checkbox', 'value' => @$values['default'] ), $options );
		
		$fieldset->addRequirement( 'database', array( 'WordCount' => array( 3, 50 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$form->submitValue = $submitValue;
		$this->setForm( $form );
    } 
	// END OF CLASS
}
