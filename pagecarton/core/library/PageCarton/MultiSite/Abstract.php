<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    PageCarton_MultiSite_Abstract
 * @copyright  Copyright (c) 2017 PageCarton (http://www.pagecarton.org)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php Wednesday 20th of December 2017 03:26PM ayoola@ayoo.la $
 */

/**
 * @see PageCarton_Widget
 */

class PageCarton_MultiSite_Abstract extends PageCarton_Widget
{
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'directory' );
 	
    /**
     * The column name of the primary key
     *
     * @var string
     */
	protected $_idColumn = 'directory';
	
    /**
     * Identifier for the column to edit
     * 
     * @var string
     */
	protected $_tableClass = 'PageCarton_MultiSite_Table';
	
    /**
     * Access level for player. Defaults to everyone
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99 );
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'PageCarton Multi-site'; 
	
    /**
     * 
     * 
     * param 
     * param void
     * param bool
     */
	public static function getNewSiteDir( $directory )  
    {
        $root = realpath( $_SERVER['DOCUMENT_ROOT'] ) ? : $_SERVER['DOCUMENT_ROOT'];        
        $newSite = $root . $directory;
        return $newSite;
    }

    /**
     * 
     * 
     * param 
     * param void
     * param bool
     */
	public static function getSiteFiles()  
    {    
        $dir = APPLICATION_DIR . DS . 'local_html';
        $files = array();
        if( is_dir( $dir ) )
        {
            $files = Ayoola_Doc::getFiles( $dir );
        }
        return $files;
    }

    /**
     * 
     * 
     * param 
     * param void
     * param bool
     */
	public static function copyFiles( $directory )  
    {    
        $newSite = self::getNewSiteDir( $directory );
        if( file_exists( $newSite ) )
        {
            return false; 
        }
        $files = self::getSiteFiles();
    //    var_export( getcwd() );
    //    var_export( $newSite );
        if( ! symlink( getcwd(), $newSite ) )
        {
            return false; 
        }
    //  Ayoola_Doc::createDirectory( $newSite );   
    //   foreach( $files as $each )   
        {
        //    copy( $each, $newSite . DS . basename( $each ) );
        }
        return true;
    }
	
    /**
     * 
     * 
     * param 
     * param void
     * param bool
     */
	public static function deleteFiles( $directory )  
    {
        $newSite = self::getNewSiteDir( $directory );
        if( ! file_exists( $newSite ) )
        {
            return false; 
        }
        Ayoola_Doc::deleteDirectoryPlusContent( $newSite );
        return true;
    }

    /**
     * creates the form for creating and editing page
     * 
     * param string The Value of the Submit Button
     * param string Value of the Legend
     * param array Default Values
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )  
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName(), 'data-not-playable' => true ) );
		$fieldset = new Ayoola_Form_Element;
		$form->submitValue = $submitValue ;
//		$form->oneFieldSetAtATime = true;
		$fieldset->placeholderInPlaceOfLabel = false;
		{
			
			Application_Javascript::addCode
			(
				'
					ayoola.addShowAutoUrl = function( target )
					{
						var element = document.getElementById( "pc_element_to_show_url" );
						element = element ? element : document.createElement( "div" );
						element.id = "pc_element_to_show_url";
						var a = false;
						if( target.value )
						{
							a = true;
						}
                        var xm = "Please enter a valid name in the space provided... (e.g. newsite)";
						if( a )
						{
                            xm = "The new site URL will be: <a href=\'' . Ayoola_Page::getRootUrl() . Ayoola_Application::getPathPrefix() . '/" + target.value + "\'>' . Ayoola_Page::getRootUrl() . Ayoola_Application::getPathPrefix() . '/" + target.value + "</a>";
						}  
						element.innerHTML = "<span style=\'font-size:x-small;\' class=\'\'>" + xm + "</span>";
						target.parentNode.insertBefore( element, target.nextSibling );
					}
				'
			);
      //      var_export( Ayoola_Application::getUrlPrefix() );
			$fieldset->addElement( array( 'name' => 'directory', 'style' => 'max-width:50%;', 'label' => 'Site Directory', 'onchange' => 'ayoola.addShowAutoUrl( this );', 'onkeyup' => 'ayoola.addShowAutoUrl( this );', 'placeholder' => 'e.g. new-site', 'type' => 'InputText', 'value' => @$values['directory'] ) ); 
//$fieldset->addFilter( 'directory','Username' );
			$fieldset->addRequirement( 'directory', array( 'NotEmpty' => array( 'badnews' => 'Directory cannot be left blank.', ), 'CharacterWhitelist' => array( 'badnews' => 'The allowed characters are lower case alphabets (a-z), numbers (0-9), underscore (_) and hyphen (-).', 'character_list' => '^0-9a-zA-Z-_\/', ), 'WordCount' => array( 1,50 ), 'DuplicateUser' => array( 'Username', 'username', 'badnews' => '"%variable%" has already been used.', ) ) );
        }
		$fieldset->addLegend( $legend );
		$fieldset->addFilters( 'StripTags::Trim' );
		$form->addFieldset( $fieldset );   
		$this->setForm( $form );
    } 
	// END OF CLASS
}
