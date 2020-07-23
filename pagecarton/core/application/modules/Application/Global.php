<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Global
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Global.php 5.11.2012 10.465am ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';


/**
 * @category   PageCarton
 * @package    Application_Global
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Global extends Ayoola_Abstract_Table
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Show Global Variables'; 
	
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
	
		if( $this->getParameter( 'include_my_info' ) )
		{
			$username = Ayoola_Application::getUserInfo( 'username' );
			if( $username )
			{
				try
				{
					if( $myInfo = Ayoola_Access::getAccessInformation( $username ) )
					{
						foreach( $myInfo as $k => $v )
						{
							$myInfo['my_'.$k] = $v;
							unset($myInfo[$k]);
						}
						$this->_objectTemplateValues += $myInfo;
					}
				}
				catch( Exception $e )
				{
				//	echo $e->getMessage();
				//	var_export( $articleInfo['username'] );
				}
			}
		}
		if( $this->getParameter( 'include_request' ) )
		{
		    $this->_objectTemplateValues += $_REQUEST;
        //    var_export( Ayoola_Application::$GLOBAL );
		}
	//	self::v( Ayoola_Application::$GLOBAL );
	}
	
	// END OF CLASS
}
