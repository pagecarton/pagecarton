<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Subscription_ShowDescription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: ShowDescription.php 5.7.2012 11.53 ayoola $
 */

/**
 * @see Application_Subscription_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Subscription_ShowDescription
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Subscription_ShowDescription extends Application_Subscription_ShowLabel
{

    /**
     * Attribute to show
     * 
     * @var string
     */
	protected static $_attribute = 'subscription_description';
		

    /**
     * The wrapper
     * 
     * @var string
     */
	protected static $_parentTag = 'p';
	
	// END OF CLASS
}
