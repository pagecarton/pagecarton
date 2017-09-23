<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_UserRestrictions
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: UserRestrictions.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_UserRestrictions
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_UserRestrictions extends Ayoola_Validator_Abstract
{
	
    /**
     * User Info to verify against
     *
     * @var array
     */
	public $badnews = 'You have exceeded your storage limit.';
	
    /**
     * User Info to verify against
     *
     * @var array
     */
	public $username = null;
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		//	By default, admin has no restrictions
		if( Ayoola_Access_Localize::hasPriviledge( 98 ) )
		{ 
			return true;   
		}
		$data = Application_User_Restrictions::getInfo( $this->username );

	//	var_export( $data );
		
		$requestedSpace = strlen( json_encode( $_POST ) );
		if( $data['free_space'] && $data['free_space'] < $requestedSpace )
		{
			$needed = ( $requestedSpace - $data['free_space'] );
			$filter = new Ayoola_Filter_FileSize();
			$needed = $filter->filter( $needed );
			$this->badnews = 'You have just ' . $data['storage_size_free'] . ' storage left. You need ' . $needed . ' space to continue. ';
			return false;
		}
		elseif( $data['max_allowed_posts'] &&  $data['posts_count_all_free'] < 1 )
		{
			$this->badnews = 'Your privileges only allow you to create ' . $data['max_allowed_posts'] . ' posts. You have exhausted that quota.';
			return false;
		}
		elseif( $data['max_allowed_posts_private'] &&   $data['posts_count_private_free'] < 1 && intval( Ayoola_Form::getGlobalValue( 'auth_level' ) ) === 97 )
		{
			if( $data['max_allowed_posts_private'] )
			{
				$this->badnews = 'Your privileges only allow you to create  ' . $data['max_allowed_posts_private'] . ' private posts.  You have exhausted that quota.';
			}
			else
			{
				$this->badnews = 'You currently do not have the privilege to create private posts';
			}
			return false;
		}
		return true;
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews() 
    {
        return $this->badnews;
    }
	// END OF CLASS
}
