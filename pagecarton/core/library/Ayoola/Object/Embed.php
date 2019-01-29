<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Embed
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Embed.php 4.11.2012 6.16pm ayoola $
 */

/**
 * @see 
 */
 
//require_once 'Ayoola/Dbase/Table/Abstract/Xml.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Object_Embed
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Object_Embed extends Ayoola_Object_Abstract   
{
	
    /**	
     *
     * @var boolean
     */
	public static $editorViewDefaultToPreviewMode = true;
	
    /**
     * For editable div in layout editor
     * 
     * @var string
     */
//	protected static $_editableTitle = "e.g. My_PHP_Class";
	
    /**
     * 
     * @var bool
     */
	public $oneObjectAtATime = false;
	
    /**
     * Use this method to blacklist classes that shouldn't be embeded'
     * This is used to stop Ayoola_Page_Editor_Layout from causing infinite loop when editing /object
     * 
     * @var array
     */
	protected static $_ignoredClasses = array();
	
    /**
     * 
     * @var array
     */
	protected static $_widgets;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {		
		try
		{			
			//	var_Export( $class );
			$classes = $this->getParameter( 'editable' ) ? : $this->getParameter( 'view' );

			//	One way or the other, leaving this causes a situation 
			//	where classes are played twice
			unset( $this->_parameter['editable'] );
			unset( $this->_parameter['view'] );

			//	don't run when page editor is invoked.
	//		var_export( $this->getParameter() );

			//	var_Export( $classes );
			$this->initiated = false; //	compatibility
			switch( $this->getParameter( 'mode' ) )
			{
				case '':
				case null:
				case false:
				default:
				//	var_export( $classes);
					if( ! is_array( $classes ) )
					{
						$classes = array_map( 'trim', explode( ',', strip_tags( $classes ) ) );
					}
				//	self::v( $classes);
					foreach( $classes as $class )
					{
						if( ! $class ){ continue; }
						if( in_array( $class, self::$_ignoredClasses ) ){ continue; }
				//		var_export( $class );
				//		var_export( self::$_ignoredClasses );
					//	$parameters = 
						$this->play( $class, $this->getParameter() );
					//	$this->play( $class ); 
					}
				//	$this->clearParametersThatMayBeDuplicated();
				break;
			}
		}
		catch( Ayoola_Exception $e )
		{ 
		//	echo $e->getMessage(); 
		}
    } 
	
    /**
     * Sets the parameters for the stage mode
     * 
     * @param array Classes
     * @param array Paramaters
     * @return null
     */
    public function setStages( $classes )
	{
		$this->getObjectStorage( 'classes' )->store( $classes );
		$this->getObjectStorage( 'todo' )->store( $classes );
	}
	
    /**
     * Add a class to the $_ignoredClasses list
     * 
     * @param string Classes
     * @return null
     */
    public static function ignoreClass( $class )
	{
		self::$_ignoredClasses[$class] = $class;
	}
	
    /**
     * Sets the parameters for the stage mode
     * 
     * @param string Class
     * @return null
     */
    public function play( $class, array $parameters = null )
	{
		$parameters = $parameters ? $parameters + array( 'return_as_object' => true ) :  array( 'return_as_object' => true );
		if( ! Ayoola_Loader::loadClass( $class ) )
		{
		//	require_once realpath( '' . $class . '.php' );
		//	self::v( $class );   
			return false;
		}
	//	self::unsetParametersThatMayBeDuplicated( $parameters );
		self::unsetParametersThatMayBeDuplicated( $this->_parameter );
		$this->_parameter['no_view_content_wrap'] = true;
		$class = $class::viewInLine( $parameters );
	//	$class = new $class( $parameters );
	//	$class->setParameter(  );
	//	$class->init();
	//	$html = $class::viewInLine( $parameters );
	//	self::v( $class->getParameter() );
		
		if( $this->getParameter( 'markup_template' ) )
		{
			$this->_parameter['markup_template'] = null;
			
			//	Workaround
			$class->getMarkupTemplate( array( 'refresh' => true ) );
		}
	//		$parameters = $class->getParameter();		
	//	unset( $this->_parameters['markup_template_no_data'] );
		unset( $this->_parameter['markup_template_no_data'] );
//			self::v( $parameters );
		$html = null;
		if( ( ! empty( $_REQUEST['rebuild_widget'] ) || ! empty( $parameters['rebuild_widget'] ) )  && method_exists( $class, 'getHTMLForLayoutEditor' ) )
		{
		//	var_export( $_REQUEST );  
		//	var_export( $parameters );  
			if( $classHtml = $class::getHTMLForLayoutEditor( $parameters ) )
			{
				$html .= '<div data-parameter_name="parent" class="pc_page_object_specific_item pc_page_object_inner_settings_area2" style="">' . $classHtml . '</div>';
			}
		}
		$html .= $class->view();
		$this->setViewContent( $html );
	}

    /**
     * Returns an array of other classes to get parameter keys from
     *
     * @param void
     * @return array
     */
    protected static function getParameterKeysFromTheseOtherClasses( & $parameters )
    {
		$classes = array();
		if( Ayoola_Loader::loadClass( @$parameters['editable'] ) )
		{
			$class = $parameters['editable'];
		//	var_export( $class );
			$classes = $class::getParameterKeysFromTheseOtherClasses( $parameters );
		}
	//	if( $parameters['editable'] )
		{

		}
//		var_export( $classes );
		$classes[] = @$parameters['editable'];
//		var_export( $classes );
		return $classes;
	}
	
    /**
	 * 
	 * Verifies if a class is valid PageCarton Widget
	 * 		
     * @param mixed Object
     * @return bool
     */
    public static function isWidget( $className, $deepCheck = true )
	{
		if( ! Ayoola_Loader::loadClass( $className ) )
		{
			return false;
		}
		if( ! is_subclass_of( $className, 'Ayoola_Abstract_Playable' ) )
		{
			return false;
		}
		$class = new ReflectionClass( $className );
		if( ! $class->isInstantiable() )
		{
			return false;
		}
		if( ! method_exists( $className, 'getObjectTitle' ) )
		{
			return false;
		}
	//	var_export( $className::getObjectTitle( false ) );
		if( $deepCheck && ! $className::getObjectTitle( false ) )
		{
			return false;
		}
		return true;
	}
	
    /**
	 * 
	 * 		
     * @return array widgets
     */
    public static function getWidgets()
	{
		$keyZ = md5( __METHOD__ . serialize( func_get_args() ) . 'fff=-' );
		
	//	if( ! empty( $options['cache'] ) )
		{
			if( ! @is_null( static::$_properties[__METHOD__][$keyZ] ) )
			{
				return static::$_properties[__METHOD__][$keyZ];
			}
			$storageInfo = array( 'id' => $keyZ, 'device' => 'File', 'time_out' => 1000000, );
			$storage = static::getObjectStorage( $storageInfo );
			if( $storage->retrieve() )
			{
			//	var_export( $storage->retrieve() );
				return (array) $storage->retrieve();
			}
		}
	//		var_export( $storage->retrieve() );
	//	var_export( $directory );
		
		$storage->store( array() );		
		static::$_properties[__METHOD__][$keyZ] = array();

		if( is_null( self::$_widgets ) ) 
		{
		//	var_export( self::$_widgets );
			$options = array();
			$files = array();
			$filter = new Ayoola_Filter_FilenameToClassname();
			
			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';  
			if( is_dir( $directory ) )
			{
				$filter->directory = $directory;
				$options = Ayoola_Doc::getFilesRecursive( $directory );  
				foreach( $options as $file )
				{
					$className = $filter->filter( $file );
					if( self::isWidget( $className ) )
					{
						$files[$className] = $className;
					}
				}
			}
	//			var_export( $directory );

			$directory = APPLICATION_PATH . DS . 'modules';  
			if( is_dir( $directory ) )
			{
				$filter->directory = $directory;
				$options = Ayoola_Doc::getFilesRecursive( $directory );  
				foreach( $options as $file )
				{
					$className = $filter->filter( $file );
					if( self::isWidget( $className ) )
					{
						$files[$className] = $className;
					}
				}
			}

			$directory = APPLICATION_DIR . DS . 'library';  
			if( is_dir( $directory ) )
			{
				$filter->directory = $directory;
				$options = Ayoola_Doc::getFilesRecursive( $directory );  
				foreach( $options as $file )
				{
					$className = $filter->filter( $file );
					if( self::isWidget( $className ) )
					{
						if( stripos( $className, 'Ayoola' ) === 0 )
						{
					//		continue;
						}
						$files[$className] = $className;
					}
				}
			}
			asort( $files );
			self::$_widgets = $files;
		}
		
		$storage->store( self::$_widgets );		
		static::$_properties[__METHOD__][$keyZ] = self::$_widgets;
		return (array) self::$_widgets;
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
	//	@$object['option'] = $object['option']  ? $object['option'] : $object['editable'];		
		{
			if( empty( $object['editable'] ) )  
			{
				$object['editable'] = 'PageCarton_Widget_Sample'; 
			}
			$html .= '<select data-parameter_name="editable" onchange="if( this.value == \'__custom\' ){  var a = prompt( \'Custom Parameter Name\', \'\' ); if( ! a ){ this.value = \'\'; return false; } var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }">';
			foreach( self::getWidgets() as $key => $value )
			{ 
				$html .=  '<option value="' . $key . '"';   
				if( @$object['editable'] == $key )
				{
					$present = true;
					$html .= ' selected = selected '; 
				}
		//		if( @$object['editable'] == $key ){ $html .= ' selected = selected '; }
				$html .=  '>' . $value . '</option>';   
			}
	//		var_export( $object );
			if( empty( $present ) )
			{
				$html .= '<option value="' . $object['editable'] . '" selected = selected>' . $object['editable'] . '</option> '; 
			}
			$html .= '<option value="__custom">Custom Widget</option> '; 
			$html .= '</select>';
			
		//	$html .= '<span style=""> or </span>';
		}
		return $html;
	}
	
	// END OF CLASS
}
