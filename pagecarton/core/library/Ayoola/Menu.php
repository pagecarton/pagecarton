<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Menu
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Menu.php 1.22.12 8.49 ayoola $
 */ 

/**     
 * @see Ayoola_Page_Menu_Abstract
 */
 
require_once 'Ayoola/Page/Menu/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Menu
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Menu extends Ayoola_Page_Menu_Abstract
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
	protected static $_accessLevel = 0;
	
    /**
     * Raw menu options for on-the-fly menu building
     *
     * @var array
     */
	protected static $_rawMenuOptions;

    /**
     * The Menu Options
     *
     * @var array
     */
	protected $_noOfOptionsToDisplay = 7;
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;
 	
    /**
     * The column name used to sort queries
     *
     * @var string
     */
	protected $_sortColumn = 'menu_name';

    /**
     * The Menu Options
     *
     * @var array
     */
	protected $_options;

    /**
     * Menu Info
     *
     * @var array
     */
	protected $_menu;
	
    /**
     * Plays the class
     */
	public function init()
    {
	//	var_export( '23342' );
	//

		//	var_export( self::hasPriviledge() );
	//	self::v( $this->getParameter( 'new_menu_name' ) );
	//	self::v( $this->getParameter( 'new_menu_name' ) );
		$menuName = $this->getViewOption() ? : $this->getParameter( 'menu_name' );
		if( ! $menuName && $this->getParameter( 'new_menu_name' ) )
		{
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$menuName = strtolower( $filter->filter( $this->getParameter( 'new_menu_name' ) ) );
		}
		$this->setParameter( array( 'markup_template_no_cache' => true, 'menu_name' => $menuName  ) ); 
	//	self::v( $this->getParameter( 'new_menu_name' ) );
		
		$this->setMenu( $menuName );		
		
		//	var_export( self::hasPriviledge() );
		if( ! $render = $this->render() )
		{
			//	update the markup template
			$this->_parameter['markup_template'] = null;
		}
		$this->setViewContent( $render );
    }
	
    /**
     * Returns _dbData for public use
     * 
     * return array
     */
	public function getPublicDbData()
    {
		return $this->getOptions();
    } 
	
    /**
     * This method sets the options property to a value
     *
     * @param array Optional to inject menu info
     */
    public function setOptions( Array $options = null )
    {
		if( $options )
		{ 
			$this->_options = $options; 
		}
		else
		{
			$this->_options = array();
		}
		$menu = $this->getMenu();
		@$menuOption = $menu['menu_options'] ? : array();
		$access = new Ayoola_Access();
		if(	( in_array( 'logged_in_hide', $menuOption )  && $access->isLoggedIn() ) 
		|| 	( in_array( 'logged_out_hide', $menuOption ) && ! $access->isLoggedIn() )
		|| 	( in_array( 'disable', $menuOption ) )
//		||	( ! $access->checkPriviledges( $menuOption['auth_level'] ) )
		)
		{
			return false;    
		}
	//	self::v( $menu );
	//	if( empty( $menu[$this->getIdColumn()] ) && empty( $menu['enabled'] ) ){ return false; }
	//	if( isset( $menu['enabled'] ) && empty( $menu['enabled'] ) ){ return false; }
		$optionTable = new Ayoola_Page_Menu_Option();
		if( @$menu[$this->getIdColumn()] )
		{
			$sortFunction2 = null;
			$sortOrder = @$menu['sort_order'] ? : $this->getParameter( 'sort_order' );
			if( $sortOrder && is_string( $sortOrder ) )
			{
				$sortOrder = array_map( 'trim', explode( ',', $sortOrder ) );
				$sortFunction2 = create_function
				( 
					'& $key, & $values', 
					'
						$key = $values["url"];
					//	var_export( $key );
					'
				); 
			}
			else
			{
				$sortOrder = array();
			}
			if( $this->getParameter( 'scope' ) === $optionTable::SCOPE_PRIVATE || in_array( 'private', $menuOption ) )
			{
			//	self::v( $menu );
			//	var_export( $this->getParameter( 'scope' ) );
				$optionTable->getDatabase()->setAccessibility( $optionTable::SCOPE_PRIVATE );
				
				//	Workaround to fix cache error array( 'fix' )
				$options = $optionTable->select( null, array( $this->getIdColumn() => $menu[$this->getIdColumn()] ), array( 'result_filter_function' => $sortFunction2, 'disable_cache' => false, 'fix' => 'fix' ) );
			}
			else
			{
				$options = $optionTable->select( null, array( $this->getIdColumn() => $menu[$this->getIdColumn()] ), array( 'result_filter_function' => $sortFunction2, 'disable_cache' => false ) );    
			}
		//	self::v( $options );  
	//		var_export( $sortOrder );  
			if( empty( $sortOrder ) )
			{
				$options = self::sortMultiDimensionalArray( $options, 'url' );
			}
			else
			{
				//	private sorting
				
				$newOptions = array();
				foreach( $sortOrder as $each )
				{
					if( ! trim( $each ) )
					{
						continue;
					}
					$newOptions[$each] = $options[$each];
					unset( $options[$each] );
					
				}
			//	var_export( $newOptions );
				$options = array_merge( $newOptions, $options );
			}
		}
	//	var_export( $options );
		//	self::v( $menu['category_name'] );
		$category = @$menu['category_name'] ? : $this->getParameter( 'category_name' );
		if( $this->getParameter( 'allow_dynamic_category_selection' ) )
		{
			if( is_numeric( $this->getParameter( 'pc_module_url_values_category_offset' ) ) )
			{
				if( @array_key_exists( $this->getParameter( 'pc_module_url_values_category_offset' ), $_REQUEST['pc_module_url_values'] ) )
				{
					$category = $_REQUEST['pc_module_url_values'][intval( $this->getParameter( 'pc_module_url_values_category_offset' ) )];
				}
				elseif( $this->getParameter( 'pc_module_url_values_request_fallback' ) && @$_REQUEST['category'] )
				{
					//	Allow request to define value
					$category = $_REQUEST['category'];
				}
			//	var_export( $category );
			}
			elseif( @$_REQUEST['category'] )
			{
				$category = $_REQUEST['category'];
			}
		}
	//	var_export( $category );
		$table = Application_Category::getInstance();
		if( ! $category && $menu['menu_options'] && in_array( 'category', $menu['menu_options'] ) )
		{
			//	Defaults to all categories available
			$categories = Application_Category_ShowAll::getPostCategories();
		//	var_export( $category );
		}
		elseif( $category )
		{
		//	$menu['category_name'] = @$menu['category_name'] ? : $this->getParameter( 'category_name' );
			$categories = $table->select( null, array( 'category_name' => $category ) );
	//	var_export( $categories );
			
			if( @count( $categories ) === 1 )
			{

				if( $childCategories = $table->select( null, array( 'parent_category' => $category ), array( 'x' ) ) )
				{
			//		$categories = $childCategories;
				}
			//	var_export( $category );
			//	var_export( $categories );
		//		$categories = $table->select();
		//		var_export( $categories );
			}
		}
		if( @$categories )	
		{
	//		self::v( $categories );
			@$menu['category_url'] = trim( $this->getParameter( 'category_url' ) ? : @$menu['category_url'] );
			@$menu['url_integration_type'] = $this->getParameter( 'url_integration_type' ) ? : @$menu['url_integration_type'];
			
			
			foreach( $categories as $each )
			{
				$subCategories = $table->select( null, array( 'parent_category' => $each['category_name'] ) );   
				if( ! empty( $subCategories ) && ! empty( $each['child_category_name'] ) && is_array( $each['child_category_name'] ) )
				{
			//		var_export( $each['child_category_name'] );
					$subCategories2 = $table->select( null, array( 'category_name' => $each['child_category_name'] ) ); 
					$subCategories += array_merge( $subCategories, $subCategories2 );
				}
			//	$subCategories = $table->select( null, array( 'child_category_name' => $each['category_name'] ) );   
				$subMenuOptions = array();
				foreach( $subCategories as $eachSub )
				{

					@$eachSub['category_url'] = $eachSub['category_url'] ? : @$menu['category_url'];
					@$eachSub['url_integration_type'] = $eachSub['url_integration_type'] ? : @$menu['url_integration_type'];
					$urlToUseForEach = Application_Article_Abstract::getPostUrl() . '/category/' . $eachSub['category_name'] . '/';
					if( $eachSub['category_url'] )
					{
						if( $eachSub['url_integration_type'] == 'pc_module_url_values_offset' )
						{
							$urlToUseForEach = rtrim( $eachSub['category_url'], '/' ) . '/' . $each['category_name'] . '/' . $eachSub['category_name'];
						}
						else
						{
							$urlToUseForEach = $eachSub['category_url'] . '?category=' . $eachSub['category_name'];
						}
						
					}
			//		var_export( $eachSub['category_url'] );  
					$subMenuOptions[$eachSub['category_name']] = array( 'option_name' => $eachSub['category_label'], 'rel' => '', 'url' => $urlToUseForEach, 'title' => $eachSub['category_description'], 'logged_in' => 1, 'logged_out' => 1, 'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => 0, 'menu_id' => 0, 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ), ) + $eachSub ? : array();
				}
				$urlToUseForEach = Application_Article_Abstract::getPostUrl() . '/category/' . $each['category_name'] . '/';
				if( $menu['category_url'] )
				{
					if( $menu['url_integration_type'] == 'pc_module_url_values_offset' )
					{
						$urlToUseForEach = rtrim( $menu['category_url'], '/' ) . '/' . $each['category_name'];
					}
					else
					{
						$urlToUseForEach = $menu['category_url'] . '?category=' . $each['category_name'];
					}
					
				}
				
				
				$options[] = array( 'option_name' => $each['category_label'], 'rel' => '', 'url' => $urlToUseForEach, 'title' => $each['category_description'], 'sub_menu_options' => $subMenuOptions, 'logged_in' => 1, 'logged_out' => 1, 'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => 0, 'menu_id' => 0, 'option_id' => 0, 'link_options' => array( 'logged_in','logged_out' ), ) + $each ? : array();
				
			}
		//	self::v( $options );
		}
		if( self::hasPriviledge( array( 99, 98 ) ) && @$menu['menu_id'] )
		{
	//		var_export( self::hasPriviledge( array( 99, 98 ) ) );
	//		var_export( self::hasPriviledge() );
			$options[] = array( 'option_name' => $this->getParameter( 'edit_option_text' ) ? : '[Manage ' . $menu['menu_label']  . ' Menu]', 'rel' => 'spotlight;', 'url' => '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Edit_List/menu_id/' . $menu['menu_id'] . '/', 'title' => 'Edit menu options', 'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => $menu['menu_id'], 'option_id' => 0, 'link_options' => array( 'spotlight','logged_in','logged_out' ), ); 
		}
		$this->_options = $options;
    } 	
	
    /**
     * Returns the Menu Property
     *
     * @param void
     * @return array
     */
    public function getOptions()
    {
		if( is_null( $this->_options ) ){ $this->setOptions(); }
		//var_export( $this->_options );
        return (array) $this->_options;
    } 	
	
    /**
     * This method sets the _noOfOptionsToDisplay property to a value
     *
     * @param int The Number of Options That is Rendered for View
     */
    public function setNoOfOptionsToDisplay( $value )
    {
		$this->_noOfOptionsToDisplay = (int) $value;
    } 	
	
    /**
     * Returns the _noOfOptionsToDisplay Property
     *
     * @param void
     * @return int The Number of Options That is Rendered for View
     */
    public function getNoOfOptionsToDisplay()
    {
        return (int) $this->_noOfOptionsToDisplay;
    } 	
	
    /**
     * Sets _menu
     *
     * @param string The menu name
     * @return bool Returns true if css file was found or created
     */
    public function setMenu( $menuName )
    {
		$table = $this->getDbTable();
	//	self::v( $this->getParameter( 'new_menu_name' ) );
	//	self::v( $menuName );  
		$menu = $table->selectOne( null, array( 'menu_name' => $menuName ) );
		if( empty( $menu[$this->getIdColumn()] ) || empty( $menu['menu_name'] ) )
		{ 
			$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
			$menu = $table->selectOne( null, array( 'menu_name' => $menuName ), array( 'work-arrewfdfound-333' => true ) );
			if( empty( $menu[$this->getIdColumn()] ) || empty( $menu['menu_name'] ) )
			{ 	
				return false; 
			}
		}
		list( $menu['document_name'], ) = explode( '.', basename( $menu['document_url'] ) );
		$this->_menu = $menu;
		//var_export( $this->_menu );
		return true;
	} 	
	
    /**
     * Returns Filename for the css for the menu
     *
     * @param string Menu Name
     */
    public static function getCssFilename( $menuName )
    {
        $dir = DOCUMENTS_DIR . DS . __CLASS__;		
		$file = $dir . DS . $menuName . FILE_CSS;
		return $file;

    } 	
	
    /**
     * Returns _rawMenuOptions
     *
     * @param string Menu Name
     * @param array Menu Option of the format array( 'enabled' => 1, 'option_id' => 11, 'option_name' => 'Home', 'url' => '/', 'title' => 'Home Page', 'logged_in' => 1, 'logged_out' => 1, 'append_previous_url' => 0, 'auth_level' => 0, 'menu_id' => '4', 'link_options' => NULL, 'sub_menu_name' => '', )
     */
    public static function setRawMenuOption( $name, $option )
    {
        self::$_rawMenuOptions[$name][$option['option_name']] = $option;
    } 	
	
    /**
     * Returns _rawMenuOptions
     *
     * @param string Menu Name
     * @return array Menu Option
     */
    public static function getRawMenuOptions( $name = null )
    {
        $response = $name ? self::$_rawMenuOptions[$name] : self::$_rawMenuOptions;
		return $response ? : array();
    } 	
	
    /**
     * Returns _menu
     *
     * @return array
     */
    public function getMenu()
    {
        return (array) $this->_menu;
    } 	
	
    /**
     * This method renders the Markup So it can be ready to be viewed 
     *
     * @param 
     * @return 
     */
    public function render()
    {
	//	self::v( $this->getParameter() );
//		var_export( $this->getOptions() );
		//	var_export( $this->getParameter( 'raw-options' ) );
	//	self::v( $this->getParameter( 'new_menu_name' ) );
		//	var_export( self::hasPriviledge() );
		if( ! $menuInfo = $this->getMenu() )
		{ 
			//	lets find out if we are injecting options
			if( ! $this->getParameter( 'raw-options' ) && ! $this->getOptions() )
			{
				//	var_export( self::hasPriviledge() );
				$menuName = $this->getParameter( 'menu_label' ) ? : $this->getParameter( 'menu_name' );
				if( self::hasPriviledge( array( 99, 98 ) ) && $menuName )
				{
					$options = array();
					$options[] = array( 'option_name' => $this->getParameter( 'sub_menu' ) ? 'Add sub-menu' : 'Set up new menu here', 'rel' => 'spotlight;', 'url' => '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Creator/?menu_name=' . ( $menuName ) . '', 'title' => 'Add another menu option', 'append_previous_url' => 0, 'enabled' => 1, 'auth_level' => array( 99, 98 ), 'menu_id' => '1', 'option_id' => 0, 'link_options' => array( 'spotlight','logged_in' ), );
					$this->setOptions( $options );      
				}
				else
				{
					return false; 
				}
			}
			else
			{
				$this->setOptions( $this->getParameter( 'raw-options' ) );
			}
		}
	//	var_export( $options );
		require_once 'Ayoola/Access.php';
		$access = new Ayoola_Access();
		$counter = 0;
		$template = null;
		$xml = new Ayoola_Xml();
		$menu = $xml->createElement( 'ul' );
		$menu->setAttribute( 'class', $this->getParameter( 'ul-class' ) );
		$this->getParameter( 'ul-id' ) ? $menu->setAttribute( 'id', $this->getParameter( 'ul-id' ) ) : null;
		
		if( get_class( $this ) === __CLASS__ ) //	Demo menus will not have classes
		{
			
			@$menu->setAttribute( 'class', __CLASS__ . $menuInfo['document_name'] . 'Container' );
		}
		/* $cssLink = $xml->createElement( 'link' );
		$cssLink->setAttribute( 'href', $menuInfo['document_url'] );
		$cssLink->setAttribute( 'rel', 'stylesheet' );
		$cssLink->setAttribute( 'type', 'text/css' );
		$menu->appendChild( $cssLink ); */
	//	var_export( $this->getOptions() );
		@Application_Style::addFile( $menuInfo['document_url'] );
		   
		//	Using menu template?
		if( $this->getParameter( 'template_name' ) )
		{
			$options = new Ayoola_Menu_Template;
			$options = $options->selectOne( null, array( 'template_name' => $this->getParameter( 'template_name' ) ) );
			$options['markup_template_prefix'] = self::replacePlaceholders( $options['markup_template_prefix'], $menuInfo + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) ); 
			$options['markup_template_suffix'] = self::replacePlaceholders( $options['markup_template_suffix'], $menuInfo + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) ); 
	//		var_export( $menuInfo );
	//		var_export( $options );
		//	markup_template_namespace
			$this->setParameter( ( $options ? : array() ) + array(  'markup_template_no_cache' => true, 'markup_template_namespace' => $this->getParameter( 'template_name' ) . $this->getParameter( 'markup_template_namespace' ) ) );
			if( @$options['javascript_files'] )
			{
				foreach( $options['javascript_files'] as $each )
				{
					Application_Javascript::addFile( $each );
				}
			}
			if( @$options['javascript_code'] )
			{
				$options['javascript_code'] = self::replacePlaceholders( $options['javascript_code'], $menuInfo + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', ) );
				Application_Javascript::addCode( $options['javascript_code'] );
			}
			if( @$options['css_files'] )
			{
				foreach( @$options['css_files'] as $each )
				{
					Application_Style::addFile( $each );
				}
			}
			if( @$options['css_code'] )
			{
				Application_Javascript::addCode( $options['css_code'] );
			}
		}
		foreach( $this->getOptions() as $values ) 
		{
			
			//	compatibility
			$options = array( 'logged_in', 'logged_out', 'append_previous_url' );
			foreach( $options as $each )
			{
				if( @is_array( $values['link_options'] ) && ! in_array( $each, $values['link_options'] ) )
				{
					$values[$each] = false;
				}
				$values[$each] = @in_array( $each, $values['link_options'] ) ? true : $values[$each];
			}
	//		var_export( $values );
	
			if( is_int( $values['auth_level'] ) )
			{
				$values['auth_level'] = array( $values['auth_level'] );
			}
			
			//	compatibility		
			$values['auth_level'] = is_array( $values['auth_level'] ) ? $values['auth_level'] : array( $values['auth_level'] );
		//	self::v( $values['auth_level'] );
	//		self::v( $values['url'] );     
	//		self::v( $values['logged_in'] );     
	//		self::v( $values['logged_out'] );     
	//		var_export( $_SERVER['REMOTE_ADDR' ] );           
/*			if(	
				( ! $values['logged_in']  && $access->isLoggedIn() && ! Ayoola_Application::isLocalServer()  ) || 
				( ! $values['logged_out'] && ! $access->isLoggedIn() && ! Ayoola_Application::isLocalServer() ) || //	Show all menu on local host
				( ! Ayoola_Abstract_Playable::hasPriviledge( $values['auth_level'] ) )
			)
*/			if(	
				( ! $values['logged_in']  && $access->isLoggedIn()   ) || 
				( ! $values['logged_out'] && ! $access->isLoggedIn() ) || //	Show all menu on local host
				( ! Ayoola_Abstract_Playable::hasPriviledge( $values['auth_level'] ) )
			)
			{   
 				continue;
			}
		//	var_export( $values );
			$option = $xml->createElement( 'li' );
			$optionClass = null;
			$linkClass = null;
			if( get_class( $this ) === __CLASS__ ) //	Demo menus will not have classes
			{
				@$optionClass .= ' ' . __CLASS__ . $menuInfo['document_name'];
			}
	//		self::v( rtrim( Ayoola_Application::getRequestedUri(), '/' ) );
	//		self::v( '_______' );
	//		self::v( rtrim( $values['url'], '/' ) );
	//		self::v( rtrim( Ayoola_Application::getRequestedUri(), '/' ) );
	//		self::v( rtrim( $values['url'], '/' ) );
			if( rtrim( Ayoola_Application::getRequestedUri(), '/' ) === rtrim( $values['url'], '/' ) )
			{
			//	self::v( $values['url'] );
				$optionClass .= 'SelectedOption ';
				$optionClass .= ' ayoolaMenuSelectedOption ';
				$optionClass .= ' ' . $this->getParameter( 'li-active-class' ) . ' ';
				$linkClass .= ' ' . $this->getParameter( 'a-active-class' ) . ' ';
				$values['li-active-class'] = $this->getParameter( 'li-active-class' ) ? : 'active';
				$values['a-active-class'] = $this->getParameter( 'a-active-class' ) ? : 'active';
			}
			else
			{
				$values['li-active-class'] = null;
				$values['a-active-class'] = null;
			//	$this->setParameter( array( 'li-active-class' => null, 'a-active-class' => null, ) );
			}
	//		$option->setAttribute( 'class', $this->getParameter( 'li-ul-class' ) );
		//	var_export( get_class( $this ) );
		//	var_export( __CLASS__ );
			if( get_class( $this ) === __CLASS__ ) //	Demo menus will not have classes
			{
				$optionClass .= ' ' . __CLASS__ . @$menuInfo['document_name'] . ' ';
			}
			if( $this->getParameter( 'length_of_option_name' ) ) 
			{
				@$values['option_name'] = strlen( $values['option_name'] ) < $this->getParameter( 'length_of_option_name' ) ? $values['option_name'] : ( trim( substr( $values['option_name'], 0, $this->getParameter( 'length_of_option_name' ) ) ) . '...' );
			}
		
			$link = @$xml->createElement( 'a', $values['option_name'] );
			
		//	$values['url'] = ! empty( $values['url'] ) ? Ayoola_Page::appendQueryStrings( array( 'menu_option' => $values['option_id'] ), $values['url'], false ) : null;
			if( Ayoola_Application::getUrlPrefix() && $values['url'][0] === '/' )
			{
				$values['url'] = Ayoola_Application::getUrlPrefix() . $values['url'];
			}
			if( ! empty( $values['append_previous_url'] ) )
			{ 
				$values['url'] = Ayoola_Page::setPreviousUrl( $values['url'] ); 
			}
			if( is_array( $values['link_options'] ) && in_array( 'spotlight', $values['link_options'] ) )
			{ 
				$values['ayoola_spotlight'] = 'ayoola.spotLight.showLinkInIFrame( \'' . $values['url'] . '\', \'page_refresh\' );';
				$link->setAttribute( 'onClick', $values['ayoola_spotlight'] );
				
				$values['url'] = 'javascript:';
			//	$link->setAttribute( 'href', 'javascript:' );
			}
			elseif( is_array( $values['link_options'] ) && in_array( 'new_window', $values['link_options'] ) )
			{
				$link->setAttribute( 'target', $values['option_name'] );
			}
			else
			{
			
				// self::hasPriviledge() && 
				if( $this->getParameter( 'auto_sub_menu' ) || @in_array( 'auto_sub_menu', $menuInfo['menu_options'] ) )
				{
					if( empty( $values['sub_menu_name'] ) && empty( $values['sub_menu_options'] ) )  
					{
						//	We only need auto-submenu if there is no submenu set
						$filter = new Ayoola_Filter_Name();
						$values['sub_menu_name'] = strtolower( substr( $filter->filter( $values['option_name'] . '_' . $values['url'] ) . '_auto_menu', 0, 30 ) );
					}
				}
			}
			$link->setAttribute( 'href', $values['url'] );
		//	$link->setAttribute( 'rel', @$values['rel'] );
		//	$link->setAttribute( 'href', $values['url'] );
			$link->setAttribute( 'title', $values['title'] ? : $values['option_name']  );
			$link->setAttribute( 'class', $linkClass );
			if( empty( $values['url'] ) )
			{ 
				$link = $xml->createHTMLElement( $values['option_name'] ); 
			}
			$option->appendChild( $link );
			$values['li-ul-class'] = null;
	//		var_export( $menuInfo );
	//		var_export( $values );
	//		exit();
			if( ( ! empty( $values['sub_menu_name'] ) || ! empty( $values['sub_menu_options'] ) ) && $values['sub_menu_name'] !== $menuInfo['menu_name'] )
			{
				$class = 'Ayoola_Menu_Demo';
				if( $subMenu = $class::viewInLine( array( 'option' => @$values['sub_menu_name'], 'raw-options' => @$values['sub_menu_options'], 'ul-class' => $this->getParameter( 'ul-1-class' ), 'ul-1-class' => 'dropdown-menu', 'li-ul-class' => 'dropdown', 'a-ul-class' => 'dropdown-toggle', 'sub_menu' => 'true', ) ) )
				{
					$values['li-ul-class'] = $this->getParameter( 'li-ul-class' ) ? : 'dropdown';
				//	self::v( $values );  
					$link->setAttribute( 'class', $this->getParameter( 'a-ul-class' ) );
					if( $this->getParameter( 'a-ul-append' ) )
					{
						$link->appendChild( $xml->createCDATASection( $this->getParameter( 'a-ul-append' ) ) );
					}
					if( $this->getParameter( 'a-ul-attributes' ) )
					{
						foreach( $this->getParameter( 'a-ul-attributes' ) as $attribute => $value )
						{
							$link->setAttribute( $attribute, $value );
						}
					}
					$optionClass .= $this->getParameter( 'li-ul-class' );
				//	var_export( $values );
				//	$class = get_class( $this );
					$values['sub_menu'] = $subMenu;
					$subMenu = $xml->createCDATASection( $subMenu );  
					$option->appendChild( $subMenu );
				}
			}
			$option->setAttribute( 'class', $optionClass );
			$menu->appendChild( $option );
			if( $this->getParameter( 'markup_template' ) )
			{
				$template .= self::replacePlaceholders( $this->getParameter( 'markup_template' ), $values + ( $this->getParameter() ? : array() ) + array( 'placeholder_prefix' => '{{{', 'placeholder_suffix' => '}}}', 'pc_no_data_filter' => true, ) );
			}
		}
		//	update the markup template
		$this->_parameter['markup_template'] = $template;
		
	//	var_export( $xml->saveHTML() );
		$xml->appendChild( $menu );
		return $xml->saveHTML();
    } 	
	
    /**
     * This method sets the _classOptions property to a value
     *
     * @param array
     * @return void
     */
    public function setClassOptions()
    {
		$table = new Ayoola_Page_Menu_Menu();

		//	look in parent tables
		$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
	//	var_export( $this->getParameter( ) );
		if( $this->getParameter( 'include_parent_menu' ) )
		{
			$all = $table->select();
		}
		else
		{
			$all = $this->getDbData();
		}
		foreach( $all as $value )
		{
			$this->_classOptions[$value['menu_name']] = $value['menu_label'];
		}
    } 	
	
    /**
     * This method returns the _classOptions property
     *
     * @param void
     * @return array
     */
    public function getClassOptions()
    {
		if( null === $this->_classOptions )
		{
			$this->setClassOptions();
		}
		return (array) $this->_classOptions;
    } 	
	
    /**
     * This method return the value of _viewOption property
     *
	 * @return mixed
     */
    public function getViewOption()
    {
		return $this->_viewOption;
    } 	
	
    /**
     * This method sets the _viewOption property to a value
     *
     * @param mixed The Value for the ViewableObject
     * @return string
     */
    public function setViewOption( $value )
    {
		//var_export( $value );
		$this->_viewOption = $value;
		try
		{
		//	$this->setMenu( $this->getViewOption() );		
		}
		catch( Ayoola_Menu_Exception $e )
		{
			return false;
		}
    } 	
	
    /**
	 * Returns text for the "interior" of the Layout Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( & $object )
	{
		$html = null;
		@$object['view'] = $object['view'] ? : $object['view_parameters'];
		@$object['option'] = $object['option'] ? : $object['view_option'];
	//	$object['xx'] = 'xxx';
	//	$html .= "<span data-parameter_name='view' >{$object['view']}</span>";
		
		//	Implementing Object Options
		//	So that each objects can be used for so many purposes.
		//	E.g. One Class will be used for any object
	//	var_export( $object['include_parent_menu'] );
		$options = $object['class_name'];
		$options = new $options( array( 'no_init' => true ) + $object );
//		$options = array();
	//	$html .= '<span style=""> Show  </span>';
	//	$newMenuName = null;
		$newMenuName = 'menu_' . time();
		static::$_counter++;
		if( ! empty( $object['include_parent_menu'] ) )
		{
			$table = new Ayoola_Page_Menu_Menu();

			//	look in parent tables
			$table->getDatabase()->setAccessibility( $table::SCOPE_PROTECTED );
			$all = $table->select( null, null, array( 'sss' => 'ss' ) );
	//		var_export( $all );

			$options = array();
			foreach( $all as $value )
			{
				$options[$value['menu_name']] = $value['menu_label'];
			}
		}
		else
		{
			$options = (array) $options->getClassOptions();
		}
		if( ! empty( $object['option'] ) && ! array_key_exists( $object['option'], $options ) )  
		{
			//	 look for it in parent tables; 
			$parentSeek = new Ayoola_Page_Menu_Menu();

			//	look in parent tables
			$parentSeek->getDatabase()->setAccessibility( $parentSeek::SCOPE_PROTECTED );
			
			if( $data = $parentSeek->selectOne( null, array( 'menu_name' => $object['option'] ), array( 'cc33' => true ) ) )
			{ 
				$options[$data['menu_name']] = $data['menu_label'];
			//	var_export( $options ); 
			}
			
		}

	//	if( $options = (array) $options->getClassOptions() )
		{
			$html .= '<span style=""> Menu:  </span>';
	//		$options = (array) $options->getClassOptions();
			$html .= '<select data-parameter_name="option">';
			$html .= '<option value="' . $newMenuName . '">New Menu</option>';
			if( empty( $object['option'] ) && ! empty( $object['new_menu_name'] ) )  
			{
				$filter = new Ayoola_Filter_Name();
				$filter->replace = '-';
				$object['new_menu_name'] = strtolower( $filter->filter( @$object['new_menu_name'] ) );
				$object['option'] = $object['option'] ? : $object['new_menu_name']; 
			}
			if( empty( $object['option'] ) )  
			{
				$object['option'] = $newMenuName; 
			}
			if( empty( $object['template_name'] ) )  
			{
				$object['template_name'] = 'HorizontalGrayish'; 
			}
		//	var_export( $object['option'] );      
			foreach( $options as $key => $value )
			{ 
				$html .=  '<option value="' . $key . '"';  
			//	var_export( $object['view'] );
				if( $object['option'] == $key  ){ $html .= ' selected = selected '; }
				$html .=  '>' . $value . '</option>';  
			}
			$html .= '</select>';
			
		//	$html .= '<span style=""> or </span>';
		}
	//	else
		{
	//		$newMenuName = 'menu_' . static::$_counter;
		}
	//	$html .= '<span style=""> new menu:  </span>';
	//	$html .= '<input value="' . ( $newMenuName ) . '" type="text" placeholder="new menu name in lowercase" data-parameter_name="new_menu_name">';
		$html .= '<span style=""> Style: </span>';
		
		$options = new Ayoola_Menu_Template;
		$options = $options->select();
		require_once 'Ayoola/Filter/SelectListArray.php';
		$filter = new Ayoola_Filter_SelectListArray( 'template_name', 'template_label');
		$options = $filter->filter( $options );
		
		$html .= '<select data-parameter_name="template_name">';
		foreach( $options as $key => $value )
		{ 
			$html .=  '<option value="' . $key . '"';   
		//	var_export( $object['view'] );
			if( @$object['template_name'] == $key ){ $html .= ' selected = selected '; }
			$html .=  '>' . $value . '</option>';  
		}
		$html .= '</select>';
	//	$html .= '<span style=""> style. </span>'; 
	//	$html .= '<button onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/Ayoola_Page_Menu_Creator/\' );">New Menu</button>'; 
		return $html;
	}

    /**
     * Singleton instance
     *
     * @var self
     */
	protected static $_instance;
	
    /**
     * Returns a singleton Instance
     *
     * @param void
     * @return self
     */
    public static function getInstance( array $parameter = null )
    {
	//	if( is_null( self::$_instance ) ){ self::$_instance = new self; }
	//	return self::$_instance;
		return new static( $parameter );
    } 	
	
    /**
     * This echos a menu
     *
     * @param string menu name
     */
/*     public static function viewInLine( $viewParameter = null, $viewOption = null )
    {
		$parameter = $viewParameter;
		if( ! is_array( $viewParameter ) )
		{
			$parameter = array( 'view' => $viewParameter, 'option' => $viewOption, );
		}
		$view = new static( $parameter );
	//	$view->setViewParameter( $viewParameter );
	//	$view->setViewOption( $viewOption );
	//	var_export( __LINE__ );
		$view->initOnce();

//		var_export( get_class( $view ) );
	//	var_export( @$view->getForm()->getValues() );
		return isset( $viewParameter['return_as_object'] ) ? $view : $view->view();
    } 	
 *//*     public static function viewInLine( $viewParameter = null, $viewOption = null )
    {
		if( is_null( $viewParameter ) && is_null( $viewOption ) )
		{
			//throw new Ayoola_Doc_Exception( 'We must have either a view parameter or option to run this method' );
		}
		$menuView = self::getInstance( array( 'option' => $viewOption, 'view' => $viewParameter ) );
	//	$menuView->setParameter( array( 'option' => $viewOption, 'view' => $viewParameter ) );
		$menuView->initOnce();
		return $menuView->view();
    } 		
 */	// END OF CLASS
}
