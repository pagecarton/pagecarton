<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Validator_Captcha
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: filename.php date time username $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Validator_Captcha
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_Captcha extends Ayoola_Validator_Abstract
{
    /**
     * The captcha db table object
     *
     * @var Ayoola_Dbase_Table_Captcha
     */
	protected $_table = null;

    /**
     * Constructor
     *
     * @param 
     * 
     */
    public function __construct()
    {
		require_once 'Ayoola/Dbase/Table/Captcha.php';
		$this->setTable( new Ayoola_Dbase_Table_Captcha ); 
    }
	
    /**
     * This method 
     *
     * @param 
     * @return 
     */
    public function method()
    {
        
    } 
	
    /**
     * This method does the db operation. Checkes if the code exist
     *
     * @param string
     * @return boolean
     */
    protected function _db( $code )
    {
		$sessionId = session_id();
		require_once 'Ayoola/Captcha.php';
		Ayoola_Captcha::cleanUp( $this->getTable() ); // Clean up old codes
		$result = $this->getTable()->selectOne( '', '', "`code` = '$code' " ); 
		return $result;
    } 
	
    /**
     * This method does the main validation
     *
     * @param 
     * @return 
     */
    public function validate( $code )
    {
        $result = $this->_db( $code );
		if( ! $result )
		return false;
		
		if( unlink( $result['filename'] ) );
        {
			$this->getTable()->delete( "`code` = '$code'" ); 
		}
		return true;
		
    } 
	
    public function setTable( $table )
    {
        $this->_table =  $table;
    }
		
    public function getTable()
    {
        return $this->_table;
    }
	
    public function getBadnews()
    {
        return '%value% - Wrong code entered - Please try again';
    }
	
	// END OF CLASS
}
