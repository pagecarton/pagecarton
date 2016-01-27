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
     * For editable div in layout editor
     * 
     * @var string
     */
	protected static $_editableTitle = "Add a list of PHP classes to embed, separated by comma.";
	
    /**
     * 
     * @var bool
     */
	public $oneObjectAtATime = false;
	
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
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object				
     * @param mixed Parameter set from the layout editor
     * @return null
     */
/*     public function setViewParameter( $parameter )
	{
		parent::setViewParameter( $parameter );
		if( ! $this->initiated )
		{
	//		$this->initiated = true;
			$this->initOnce();
		}
	}
 */
    /**
	 * Just incoporating this - So that the layout can be more interative
	 * The layout editor will be able to pass a parameter to the viewable object				
     * @param mixed Parameter set from the layout editor
     * @return null
     */
/*     public function setParameter( array $parameter )
	{
		$this->_parameter = $parameter ;
	//	var_export( $this->initiated );
		if( ! $this->initiated )
		{
		//	$this->initiated = true;
			$this->initOnce();
		}
	}
 */	// END OF CLASS
}
