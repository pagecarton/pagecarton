<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Status
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Status.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Extension_Import_Abstract
 */
 
require_once 'Application/Subscription/Abstract.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Extension_Import_Status
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Extension_Import_Status extends Ayoola_Extension_Import_Abstract
{	
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Plugin Status Update'; 
	
    /**
     * Switch data
     * 
     */
	public static function change( $data, $currentStatus = true )  
    {
     //   var_export( $data['extension_name'] );
    //    var_export( $data );
    //    $test = Ayoola_Extension_Import_Table::getInstance()->select( null, array( 'extension_name' => $data['extension_name'] ) );

    //    var_export( $test );

        //  manage dependencies first
        $update = array();
        if( $currentStatus )
        {
            $update['status'] = 'Disabled';
        }
        else
        {
            $update['status'] = 'Enabled';
        }

        if( ! empty( $data['dependencies'] ) )
        {
            //  this can take some time for some recursive dependency check
            //  example a long list of dependencies
            $method = __METHOD__;
            if( $currentStatus )
            {
            //    var_export( $data );
                //  switch off depencies installed specifically for this plugin
                if( ! empty( $data['installed_dependencies'] ) )
                {
                    $installations = Ayoola_Extension_Import_Table::getInstance()->select( null, array( 'article_url' => $data['installed_dependencies'] ) );
                    //    var_export( $data['installed_dependencies'] );
                    foreach( $installations as $dependencyData )
                    {
                    //       var_export( $dependencyData['extension_name'] );
                        $method( $dependencyData, $currentStatus );
                    }
                }
            }
            else
            {
                foreach( $data['dependencies'] as $dependency )
                {
                    if( empty( $dependency ) )
                    {
                        continue;
                    }
                    if( ! $dependencyData = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'article_url' => $dependency ) ) )
                    {
                        $data['installed_dependencies'][] = $dependency;
                        $data['installed_dependencies'] = array_unique( $data['installed_dependencies'] );
                        //  update dependency in db
                        if( ! Ayoola_Extension_Import_Table::getInstance()->update( 
                            array( 'installed_dependencies' => $data['installed_dependencies'] ),
                            array( 'article_url' => $data['article_url'] )
                         ) )
                        {
                            throw new Ayoola_Extension_Import_Exception( 'Dependency ' . $dependency . ' could not be added to database for ' . $data['extension_title'] );
                        }
                        Ayoola_Extension_Import_Repository::install( $dependency );
                        if( ! $dependencyData = Ayoola_Extension_Import_Table::getInstance()->selectOne( null, array( 'article_url' => $dependency ) ) )
                        {
                            throw new Ayoola_Extension_Import_Exception( 'Dependency ' . $dependency . ' could not be installed' );
                        }

                    }
                    $method( $dependencyData, $currentStatus );
                }
                $alreadyMetDepencencies = array_diff( $data['dependencies'], $data['installed_dependencies'] );
                if( $alreadyMetDepencencies != $data['ready_dependencies'] )
                {
                    if( ! Ayoola_Extension_Import_Table::getInstance()->update( 
                        array( 'ready_dependencies' => $alreadyMetDepencencies ),
                        array( 'article_url' => $data['article_url'] )
                        ) )
                    {
                        throw new Ayoola_Extension_Import_Exception( 'We were not able to set already met dependencies for ' . $data['extension_title'] );
                    }
                }
            }
        }
        
		$fromDir = ( @constant( 'EXTENSIONS_PATH' ) ? Ayoola_Application::getDomainSettings( EXTENSIONS_PATH ) : ( APPLICATION_DIR . DS . 'extensions' ) ) . DS . $data['extension_name'] . DS . 'application';
        $toDir = Ayoola_Application::getDomainSettings( APPLICATION_PATH );

		if( @$data['modules'] )
		{
			$directory =   '/modules';
			foreach( $data['modules'] as $key => $each )
			{						
				$from = $fromDir . $directory . $each;
				$to = $toDir . $directory . $each;
                if( self::changeStatus( $currentStatus, $from , $to ) )
                {

                }
			}
		}
		if( @$data['databases'] )
		{
			$directory =  '/databases';
			foreach( $data['databases'] as $each )
			{
				$from = $fromDir . $directory . $each;
				$to = $toDir . $directory . $each;
				$databaseExtension = dirname( $to ) . '/__/' . array_shift( explode( '.', basename( $each ) ) ) . '/extensions/' . $data['extension_name'] . '.xml';
                self::changeStatus( $currentStatus, $from , $databaseExtension );
                
                //  Supplementary files
                $from = $fromDir . $directory . dirname( $each ) . DS . '__' . DS . array_shift( explode( '.', basename( $each ) ) );
                $to = dirname( $databaseExtension ) . DS . '' . $data['extension_name'] . '/supplementary';

				self::changeStatus( $currentStatus, $from , $to );
			}
		}
		if( @$data['documents'] )
		{
			$directory =  '/documents';
			foreach( $data['documents'] as $each )
			{
				$from = $fromDir . $directory . $each;
				$to = $toDir . $directory . $each;
				self::changeStatus( $currentStatus, $from , $to );
			}
		}
		if( @$data['plugins'] )
		{
			$directory =  '/plugins/';
			foreach( $data['plugins'] as $each )
			{
				$from = $fromDir . $directory . $each;
				$to = $toDir . $directory . $each;
				self::changeStatus( $currentStatus, $from , $to );
			}
		}
		if( @$data['pages'] )
		{
			$directory =  '/';
			foreach( $data['pages'] as $uri )
			{
				if( $pagePaths = Ayoola_Page::getPagePaths( $uri ) )
				{
					foreach( $pagePaths as $each )
					{
						$from = $fromDir . $directory . $each;
						$to = $toDir . $directory . $each;
						self::changeStatus( $currentStatus, $from , $to );
					}
				}
                $sanitizeClass = new Ayoola_Page_Editor_Sanitize( array( 'no_init' => true, 'url' => $uri, 'auto_create_page' => true ) );  
                $sanitizeClass->refresh( $uri );	     		
			}
		}
		if( @$data['templates'] )
		{
			$directory =  '/documents/layout/';
			foreach( $data['templates'] as $each )
			{
				$from = $fromDir . $directory . $each;
				$to = $toDir . $directory . $each;
				self::changeStatus( $currentStatus, $from , $to );
			}
        }
    //    var_export( array( 'status' => $data['status'] ) );
    //    var_export( array( 'extension_name' => $data['extension_name'] ) );
        Ayoola_Extension_Import_Table::getInstance()->update( $update, array( 'extension_name' => $data['extension_name'] ) );
        return true;
    }
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()  
    {
		try{ $this->setIdentifier(); }
		catch( Ayoola_Extension_Import_Exception $e ){ return false; }
		if( ! $data = self::getIdentifierData() ){ return false; }

		$currentStatus = true;
		if( $this->getParameter( 'switch' ) === 'off' )
		{
			//	Try to switch this off whether its previously on/off
			$data['status'] = '1';
		}
		switch( strtolower( strval( $data['status'] ) ) )
		{
			case 'enabled':
			case '1':
				// we currently are on
				$currentStatus = true;
				
				//	Switch off				
				$data['status'] = 'Disabled';
				$this->createConfirmationForm( 'Disable Plugin...', 'Disable "' . $data['extension_title'] . '"', $data );
			break;
			default:
				// we currently are off
				$currentStatus = false;
				
				//	Switch on
				$this->createConfirmationForm( 'Enable Plugin...', 'Enable "' . $data['extension_title'] . '"', $data );
				$data['status'] = 'Enabled';
			break;
		}
		$this->setViewContent( $this->getForm()->view(), true );
		if( ! $values = $this->getForm()->getValues() )
		{ 
			if( $this->getParameter( 'switch' ) !== 'off' )
			{
				return false; 
			}
        }

        //  switch
		$this->setViewContent(  '' . self::__( '<span></span> ' ) . '', true  );
		$settings = null;
 		if( ! self::change( $data, $currentStatus ) )
		{ 
			$this->setViewContent( self::__( '<p class="badnews">Error: could not save Plugin.</p>.' ) ); 
			return false;
		}
		if( $data['settings_class'] )
		{ 
			if( Ayoola_Loader::loadClass( $data['settings_class'] ) )
			{
				$settings =  '<a href="' . Ayoola_Application::getUrlPrefix() .  '/tools/classplayer/get/name/Ayoola_Extension_Import_Settings/?extension_name=' . $data['extension_name'] . '">Manage Settings.</p>';
			}

		}
		$this->setViewContent( self::__( '<p class="boxednews goodnews">Plugin switch "' . $data['status'] . '" successfully. ' . $settings . '</p>' ) );

		//	clear cache
		Application_Cache_Clear::viewInLine();	

  
	} 
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected static function changeStatus( $currentStatus, $from, $to )  
    {
		$file = str_ireplace( Ayoola_Application::getDomainSettings( APPLICATION_PATH ), '', $to );
		$from = str_replace( array( '/', '\\' ), DS, $from );
		$to = str_replace( array( '/', '\\' ), DS, $to );
		switch( $currentStatus )
		{
			case true:
				if( ! is_link( $to ) )
				{
					return false;
				}				
				elseif( $from !== readlink( $to ) && is_file( readlink( $to ) ) )
				{
					return false;
				}				
				unlink( $to );
				Ayoola_Doc::removeDirectory( basename( $to ) );
			break;
			case false:
				if( ! file_exists( $from ) )
				{
					return false;
				}					
				elseif( file_exists( $to ) )
				{
					return false;
				}					
				//	create this dir if it isnt there before
                Ayoola_Doc::createDirectory( dirname( $to ) );
				symlink( $from , $to );
			break;
		}
		return true;
	}
	// END OF CLASS
}
