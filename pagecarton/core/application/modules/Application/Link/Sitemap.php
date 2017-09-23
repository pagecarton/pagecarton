<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Application_Link_Sitemap
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Sitemap.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Link_Abstract
 */
 
require_once 'Application/Link/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Application_Link_Sitemap
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Link_Sitemap extends Application_Link_Abstract
{

    /**
     * The xml document
     * 
     * @var Ayoola_Xml
     */
	protected $_xml;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		try
		{
			$this->createConfirmationForm( 'Build Site Map', 'Build site map and submit it to search engines' );
			$this->setViewContent( $this->getForm()->view(), true );
			if( ! $values = $this->getForm()->getValues() ){ return false; }
			
			$domains = new Application_Domain();
		//	echo $domains->view();
			Ayoola_Application::getDomainSettings();
			if( ! $domains = $domains->select() )
			{
				
			}
			$directory = Ayoola_Application::getDomainSettings( APPLICATION_PATH ) . DS . DOCUMENTS_DIR . DS . 'sitemap';
			Ayoola_Doc::createDirectory( $directory );
			$extentionXml = '.xml';
			$extentionGz = '.tar.gz';
			foreach( $domains as $domain )
			{
				$this->setXml( $domain['domain_name'] );
				$filenameXml = $directory . DS . $domain['domain_name'] . $extentionXml;
				$filenameGz = $directory . DS . $domain['domain_name'] . $extentionGz;
				$tempFileName = rand( 1000, 2000 );
				$tempFile = $directory . DS . $tempFileName . '.tar';
				
				//	save the xml site map
				$this->getXml()->save( $filenameXml );
				
				//	compress the sitemap to send to search engine
				@unlink( $filenameGz ); // remove the previous compressed sitemap
				@unlink( $tempFile ); // remove the previous tempfile
				$phar = 'Ayoola_Phar_Data';
				$temp = new $phar( $tempFile );
				$temp->startBuffering();
			//	var_export( $filenameXml );
			//	var_export( $filenameGz );
				$temp->addFile( $filenameXml, 'sitemap.xml' );
				$temp->stopBuffering();
				$temp->compress( Ayoola_Phar::GZ ); 
				unset( $temp );
				unlink( $tempFile );
				
				//	compression have changed the filename
				rename( $tempFile . '.gz', $filenameGz );			
				
				$url = urlencode( 'http://' . $domain['domain_name'] . '/sitemap/' . $domain['domain_name'] . '.tar.gz' );
				$this->sendToSearchEngine( $url );
			}

		}
		catch( Exception $e ){ return false; }
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Send sitemap to search engines
     * 
     */
	public function sendToSearchEngine( $url )
    {
		$searchEngines = new Application_Link_SearchEngine();
		$searchEngines = $searchEngines->select();
			//	var_export( $searchEngines );
	//	$headers = array( "Content-Type: text/xml; charset=UTF-8" );
		foreach( $searchEngines as $searchEngine )
		{
			$requestUrl = str_ireplace( 'sitemap_url', $url, $searchEngine['searchengine_sitemap_url'] );
		//	var_export( '<br />' . $requestUrl . '<br />' );
			$request = curl_init( $requestUrl );
			curl_setopt( $request, CURLOPT_HEADER, false );
		//	curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $request, CURLOPT_FOLLOWLOCATION, false );
		//	var_export( $request );
			$response = curl_exec( $request );
		//	var_export( $response );
			// close cURL resource, and free up system resources
			curl_close( $request );
		}
		return true;
    } 
	
    /**
     * Returns the Xml
     * 
     * @return Ayoola_Xml
     */
	public function getXml()
    {
		if( is_null( $this->_xml ) ){ $this->setXml( DOMAIN ); }
		return $this->_xml;
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml( $domainName )
    {
		$this->_xml = new Ayoola_Xml();
		$urlset = $this->_xml->createElement( 'urlset' );
		$urlset->setAttribute( 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9' );
		$this->_xml->appendChild( $urlset );
		$tables[] = new Ayoola_Page_Page();
		$tables[] = new Application_Link();
		foreach( $tables as $table )
		{
			$each = $table->select();
		//	var_export( $each );
			foreach( $each as $data )
			{
				$url = $this->_xml->createElement( 'url' );
				$url = $urlset->appendChild( $url );
				$data['url'] = isset( $data['url'] ) ? $data['url'] : $data['link_url'];
				$data['url'] = isset( $data['link_name'] ) ? '/' . $data['link_name'] . '/' : $data['url'];
				$data['url'] = 'http://' . $domainName . $data['url'];
				$loc = $this->_xml->createElement( 'loc', $data['url'] );
				$loc = $url->appendChild( $loc );
				$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
				$changefreq = $url->appendChild( $changefreq );
				$defaultPriority = 5;
				$data['link_priority'] = isset( $data['link_priority'] ) ? $data['link_priority'] : $defaultPriority;
				$priority = $this->_xml->createElement( 'priority', '0.' . $data['link_priority'] );
				$priority = $url->appendChild( $priority );
			}
		}
    } 
	// END OF CLASS
}
