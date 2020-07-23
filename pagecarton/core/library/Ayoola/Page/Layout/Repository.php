<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Repository
 * @copyright  Copyright (c) 2018 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Repository.php Monday 14th of May 2018 07:32PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class Ayoola_Page_Layout_Repository extends Ayoola_Extension_Import_Repository
{
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Browse Themes'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_site = 'themes.pagecarton.org'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginType = 'theme'; 
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_pluginClass = 'Ayoola_Page_Layout_Creator'; 

    
    /**
     * 
     * 
     */
	public function getDefaultCategory()
    {
        return Application_Settings_SiteInfo::retrieve( 'site_type' );
    }
    
    /**
     * 
     * 
     */
	public function noContentDefault()
    {
        if( $this->getDbData() )
        {
            //var_export( $this->getDbData() );
        }
        else
        {
        //    $this->setViewContent(  '' . self::__( '<h2 class="">Upload theme manually</h2>' ) . '', true  );
            $this->setViewContent( self::__( '<p class="pc-notify-info">Connect to the internet to download themes directly from <a target="_blank" href="https://themes.pagecarton.org">PageCarton Themes </a> or download manually and upload here. <a target="_blank" href="https://themes.pagecarton.org"><i class="fa fa-external-link pc_give_space"></i> PageCarton Themes </a></p>' ) );
            $this->setViewContent( Ayoola_Page_Layout_Creator::viewInLine() );
            return false;
        }
    }
	
    
    /**
     * Overides the parent class
     * 
     */
	public static function getOtherInstallOptions( $filename )
    {
        $options =  array( 
                            'auto_section',  
                            'auto_menu', 
                        ); 
        $values = array( 
                            'path' => $filename,
                            'layout_label' => $_GET['title'],
                            //   'theme_url' => 'cccccc',
                            'layout_options' => $options,
                            'layout_type' => 'upload',
                            'article_url' => $_GET['install'],
        );
        return $values;
    }
		
    /**
     * 
     * 
     */
	public static function getPercentageCompleted()
    {
        $percentage = 0;
    //    Ayoola_Page_PageLayout::getInstance();
        $table = Ayoola_Page_PageLayout::getInstance( Ayoola_Page_PageLayout::SCOPE_PRIVATE );
		$table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
		$table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
		$response = $table->select();
     //   var_export( $response );
		if( $table->select() )
		{
			$percentage += 100;
		}
		return $percentage;
	}
	// END OF CLASS
}
