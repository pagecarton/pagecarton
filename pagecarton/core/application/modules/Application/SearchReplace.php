<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_SearchReplace
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: SearchBox.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */

/**
 * @category   PageCarton
 * @package    Application_SearchReplace
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_SearchReplace extends Ayoola_Abstract_Table
{
	/**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Search & Replace'; 
	
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
	protected static $_accessLevel = 0;
	
    /**
     * Performs the process
     * 
     */
	public function init()
    {
		try
		{
            $search = $this->getParameter( 'search' );
            $replace = $this->getParameter( 'replace' );

            $this->_parameter['markup_template'] = str_ireplace( $search, $replace, $this->_parameter['markup_template'] );

        }
		catch( Ayoola_Exception $e ){ return false; }
	}
	
	// END OF CLASS
}
