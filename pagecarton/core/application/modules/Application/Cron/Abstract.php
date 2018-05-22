<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @advert   Ayoola
 * @package    Application_Cron_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_Cron_Exception 
 */
 
require_once 'Application/Cron/Exception.php';


/**
 * @advert   Ayoola
 * @package    Application_Cron_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_Cron_Abstract extends Ayoola_Abstract_Table
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
	protected $_identifierKeys = array( 'cron_id' );
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'Application_Cron';
	
    /**
     * 
     * 
     */
	protected static function getCron()
    {
		$cron = exec( 'crontab -l', $output );
	//	var_export( $cron );
	//	var_export( $output );
	//	$cron = $cron ? explode( PHP_EOL, $cron ) : array();
		return $output;
	}
	
    /**
     * 
     * 
     */
	protected static function update( array $updates )
    {
	//	if( ! $values = $this->getForm()->getValues() ){ return false; }
//		$data = $this->getDbData();
		$cron = self::getCron();
	//	var_export( $cron );
		$deleted = 0;
		foreach( $updates as $key => $each )
		{
			if( is_string( $each ) ){ $updates[$key] = array_map( 'trim', explode( ',', $each ) ); }
			
		}
		foreach( $cron as $key => $each )
		{
			//	First remove the unwanted or duplicate jobs.
			if( is_array( $updates['delete'] ) && in_array( $each, $updates['delete'] ) )
			{ 
				$deleted++;
				unset( $cron[$key] ); 
			}
			if( is_array( $updates['insert'] ) && in_array( $each, $updates['insert'] ) ){ unset( $cron[$key] ); }
		}
		
		//	Insert new jobs
		if( is_array( $updates['insert'] ) ){ $cron = array_merge( $updates['insert'], $cron ); }
		if( is_array( @$updates['delete'] ) && count( $updates['delete'] ) != $deleted ){ throw new Application_Cron_Exception( 'THE FOLLOWING WAS NOT DELETED: ' . implode( '; ', $updates['delete'] ) ); }
		$cron = implode( PHP_EOL, $cron ) . PHP_EOL;
	//	var_export( $cron );
		self::save( $cron );
    } 
	
    /**
     * 
     * 
     */
	protected static function save( $cron )
    {
		$filename = tempnam( 'cron_' . microtime() , '.txt' );
		file_put_contents( $filename, $cron );
		$before = self::getCron();
		$output = shell_exec( "crontab {$filename}" );
		unlink( $filename );
		$after = self::getCron();
		if( $before == $after )
		{ 
			throw new Application_Cron_Exception( 'Crontab could not be saved.' ); 
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
	//	var_export( $values['days_of_the_week'] );
	
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->addElement( array( 'name' => 'task', 'description' => 'e.g. 30 8 * * 6 /path/to/file.sh >/dev/null 2>&1 ', 'type' => 'InputText', 'value' => @$values['task'] ) );
		$fieldset->addRequirement( 'task', array( 'WordCount' => array( 2, 100 ) ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$form->submitValue = $submitValue;
		$this->setForm( $form );
    } 
	// END OF CLASS
}
