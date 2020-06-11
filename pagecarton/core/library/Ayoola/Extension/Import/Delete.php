<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Delete.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Delete
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_Delete extends Ayoola_Extension_Import_Abstract
{
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Remove plugin'; 
		
    /**
     * 
     * 
     */
	public static function this( $extensionName )
    {
        //	Disable extension
        $where = array( 'extension_name' => $extensionName );
        if( ! $extension = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, $where ) )
        {
            throw new Ayoola_Extension_Import_Exception( 'Extension ' . $extensionName . ' not found in the database' );
        }

        //  delete dependencies first
        if( ! empty( $extension['installed_dependencies'] ) )
        {
            foreach( $extension['installed_dependencies'] as $dependency )
            {
                $dependencyData = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'article_url' => $dependency ) );


                $otherDependencies = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'ready_dependencies' => $dependency ) );

                if( $otherDependencies )
                {
                    //  other plugins are dependent on this one
                    continue;
                }
                self::this( $dependencyData['extension_name'] );
            }
        }
         if( ! empty( $extension['ready_dependencies'] ) )
        {
            foreach( $extension['ready_dependencies'] as $dependency )
            {
                $dependencyData = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'article_url' => $dependency ) );


                $mainDepender = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'installed_dependencies' => $dependency ) );

                if( $mainDepender )
                {
                    //  other plugins are dependent on this one
                    continue;
                }
                self::this( $dependencyData['extension_name'] );
            }
        }
 
        Ayoola_Extension_Import_Status::change( $extension, true );
    //    $class = new Ayoola_Extension_Import_Status( array( 'switch' => 'off' ) + $where );
    //    $class->init();
        
        //	remove files
        $dir = @constant( 'EXTENSIONS_PATH' ) ? Ayoola_Application::getDomainSettings( EXTENSIONS_PATH ) : ( APPLICATION_DIR . DS . 'extensions' );
        $dir = $dir . DS . ( $extensionName ? : 'avoid deleting all directories' );
        if( is_dir( $dir ) )
        {
            Ayoola_Doc::removeDirectory( $dir, true );
        }
        return Ayoola_Extension_Import_Table::getInstance()->delete( $where );
    //    return true;
    }
		
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{ 
			if( ! $data = $this->getIdentifierData() ){ return false; }
			$this->createDeleteForm( $data['extension_title'] );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			//  delete from db	
			if( self::this( $data['extension_name'] ) )
			{ 
				$this->setViewContent(  '' . self::__( '<p class="goodnews">Plugin deleted successfully</p>' ) . '', true  ); 
			} 
		}
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
    } 
	// END OF CLASS
}
