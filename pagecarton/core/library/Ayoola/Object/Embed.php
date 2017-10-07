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

			//	var_Export( $classes );
			$this->initiated = false; //	compatibility
			switch( $this->getParameter( 'mode' ) )
			{
/* 				case 'stages':
					//	Use classnames as key
					if( ! is_array( $classes ) )
					{
						$classes = array_map( 'trim', explode( ',', $classes ) );
						$classes = array_combine( $classes, $classes );
						$classes = array_fill_keys( $classes, null );
					}
					$this->setStages( $classes );
					$class = key( $classes );
					$this->getObjectStorage( 'current' )->store( $class );
					$this->play( $class, array_shift( $classes ) );
					$this->getObjectStorage( 'todo' )->store( $classes );
				break;
 */				case '':
				case null:
				case false:
				default:
				//	var_export( $classes);
					if( ! is_array( $classes ) )
					{
						$classes = array_map( 'trim', explode( ',', strip_tags( $classes ) ) );
					}
				//	var_export( $classes);
					foreach( $classes as $class )
					{
						if( ! $class ){ continue; }
						if( in_array( $class, self::$_ignoredClasses ) ){ continue; }
				//		var_export( $class );
				//		var_export( self::$_ignoredClasses );

						$this->play( $class, $this->getParameter() );
					//	$this->play( $class ); 
					}
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
	//	if( ! Ayoola_Loader::loadClass( $class ) )
		{
		//	return false;
		//	throw new Ayoola_Object_Exception( 'EMBEDDED OBJECT NOT AVAILABLE: ' . $class );
		}
	//	$this->initiated = true; //	compatibility
	//	$class->initiated = true; //	compatibility
//		self::v( $class );
		$parameters = $parameters ? $parameters + array( 'return_as_object' => true ) :  array( 'return_as_object' => true );
		if( ! Ayoola_Loader::loadClass( $class ) )
		{
		//	require_once realpath( '' . $class . '.php' );
		//	self::v( $class );   
			return false;
		}
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
		$html = $class->view();
		
	//	self::v( $this->getParameter( 'markup_template' ) );
	//	self::v( $html );
	//	self::v( $class->viewInLine() );
	//	if( is_object( $content ) ) var_export( $content );
	//	var_export( @$class->getForm()->getValues() );
		$this->setViewContent( $html );
		
		//	Return the values from the forms
	//	if( ! @$values = $class->getForm()->getValues() ){ return; }
	//	var_export( @$class );
	//	var_export( @$class->getForm()->getValues() );
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
		
	
		$options = array();
		$files = array();

		$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . 'modules';  
		if( is_dir( $directory ) )
		{
			$options = Ayoola_Doc::getFilesRecursive( $directory );  
		
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = implode( '_', array_map( 'ucwords', explode( '_', array_shift( explode( '.', trim( str_replace( DS, '_', $file ), '_' ) ) ) ) ) );
				if( ! Ayoola_Loader::loadClass( $className ) )
				{
					continue;
				}
				if( ! is_subclass_of( $className, 'Ayoola_Abstract_Playable' ) )
				{
					continue;
				}
				$class = new ReflectionClass( $className );
				if( ! $class->isInstantiable() )
				{
					continue;
				}
				$files[$className] = $className;
			}
		}
//			var_export( $directory );

		$directory = APPLICATION_PATH . DS . 'modules';  
		if( is_dir( $directory ) )
		{
			$options = Ayoola_Doc::getFilesRecursive( $directory );  
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = implode( '_', array_map( 'ucwords', explode( '_', array_shift( explode( '.', trim( str_replace( DS, '_', $file ), '_' ) ) ) ) ) );
				if( ! Ayoola_Loader::loadClass( $className ) )
				{
					continue;
				}
				if( ! is_subclass_of( $className, 'Ayoola_Abstract_Playable' ) )
				{
					continue;
				}
				$class = new ReflectionClass( $className );
				if( ! $class->isInstantiable() )
				{
					continue;
				}
				$files[$className] = $className;
			}
		}

		$directory = APPLICATION_DIR . DS . 'library';  
		if( is_dir( $directory ) )
		{
			$options = Ayoola_Doc::getFilesRecursive( $directory ); 
			foreach( $options as $file )
			{
				$directory = str_ireplace( DS, '/', $directory );
				$file = str_ireplace( DS, '/', $file );
	//			var_export( $directory );
	//			var_export( $file );
				$file = str_ireplace( $directory, '', $file );
				
				//	The label is transformed into the class value
				$className = implode( '_', array_map( 'ucwords', explode( '_', array_shift( explode( '.', trim( str_replace( DS, '_', $file ), '_' ) ) ) ) ) );
				if( ! Ayoola_Loader::loadClass( $className ) )
				{
					continue;
				}
				if( ! is_subclass_of( $className, 'Ayoola_Abstract_Playable' ) )
				{
					continue;
				}
				$class = new ReflectionClass( $className );
				if( ! $class->isInstantiable() )
				{
					continue;
				}
				if( stripos( $className, 'pagecarton' ) === 0 )
				{
					$files[$className] = $className;
				}
			}
		}
		asort( $files );


		{
			if( empty( $object['editable'] ) )  
			{
				$object['editable'] = 'PageCarton_Widget_Sample'; 
			}
			$html .= '<select data-parameter_name="editable">';
			foreach( $files as $key => $value )
			{ 
				$html .=  '<option value="' . $key . '"';   
				if( @$object['editable'] == $key ){ $html .= ' selected = selected '; }
				$html .=  '>' . $value . '</option>';  
			}
			$html .= '</select>';
			
		//	$html .= '<span style=""> or </span>';
		}
		return $html;
	}
	
	// END OF CLASS
}
