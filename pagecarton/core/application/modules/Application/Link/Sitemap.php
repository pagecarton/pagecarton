<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
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
 * @category   PageCarton
 * @package    Application_Link_Sitemap
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Link_Sitemap extends Application_Link_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'Sitemap'; 

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
			//	do normal
			Ayoola_Application::reset();
			$this->setXml();

			$table = new PageCarton_MultiSite_Table();
			if( $sites = $table->select() )
			{
				
			}

			//	do for directories
			foreach( $sites as $site )
			{
				Ayoola_Application::reset( array( 'path' => $site['directory'] ) );
				$this->setXml();
			}
			Ayoola_Application::reset();

			switch( strtolower( $_REQUEST['mode'] ) )
			{
				case 'html':
				//	echo $this->_html->view();
				//	exit();
					$this->setViewContent( $this->_html->saveHTML() );
				break;
				case 'xml':
					echo $this->_xml->view();
					exit();
				break;
				case 'html-xml':
					echo $this->_html->view();
					exit();
				break;
				default:
					$this->setViewContent( $this->_html->saveHTML() );
/*					$content = '<p>Site Map</p><ul>';
					$content .= '<li><a href="?mode=html">HTML</a></li>';
					$content .= '<li><a href="?mode=xml">XML</a></li>';
					$content .= '</ul>';
					$this->setViewContent( $content );
*/				break;
			}
	//		exit();

		}
		catch( Exception $e ){ return false; }
	//	var_export( $this->getDbData() );
    } 
	
    /**
     * Sets the xml
     * 
     */
	public function setXml()
    {
		if( empty( $this->_xml ) )
		{
			$this->_xml = new Ayoola_Xml();
			$urlset = $this->_xml->createElement( 'urlset' );
			$urlset->setAttribute( 'xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9' );
			$this->_xml->appendChild( $urlset );
			$this->_xml->appendChild( $urlset );

			$this->_html = new Ayoola_Xml();
			$ul = $this->_html->createElement( 'ul' );
			$ul = $this->_html->appendChild( $ul );
		}
		@$urlset = $urlset ? : $this->_xml->documentElement;
		@$ul = $ul ? : $this->_html->documentElement;

		//	each site
		$li = $this->_html->createElement( 'li', Ayoola_Application::getPathPrefix() ? : 'Root' );
		$li = $ul->appendChild( $li );

		//	pages

		$table = new Ayoola_Page_Page();
		$table = $table->select();

		$innerUl = $this->_html->createElement( 'ul' );
		$innerUl = $li->appendChild( $innerUl );
		$innerLi = $this->_html->createElement( 'li', 'Pages' );
		$innerLi = $innerUl->appendChild( $innerLi );

		$innerInnerUl = $this->_html->createElement( 'ul' );
		$innerInnerUl = $innerLi->appendChild( $innerInnerUl );


		foreach( $table as $data )
		{
			if( ! @in_array( '0', $data['auth_level'] ) || @in_array( 'module', $data['page_options'] ) )
			{  
				continue;
			}
			$url = $this->_xml->createElement( 'url' );
			$url = $urlset->appendChild( $url );
			$data['url'] = '' . Ayoola_Page::getHomePageUrl() . $data['url'];
			$loc = $this->_xml->createElement( 'loc', $data['url'] );
			$loc = $url->appendChild( $loc );
			$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
			$changefreq = $url->appendChild( $changefreq );
			$defaultPriority = 8;
			$priority = $this->_xml->createElement( 'priority', '0.' . $defaultPriority );
			$priority = $url->appendChild( $priority );
				
			$a = $this->_html->createElement( 'a', htmlspecialchars( $data['title'] ? : $data['url'] ) );
			$a->setAttribute( 'href', $data['url'] );
			$a->setAttribute( 'title', htmlspecialchars( $data['description'] ) );
				
			$pageLi = $this->_html->createElement( 'li' );
			$pageLi = $innerInnerUl->appendChild( $pageLi );
			$a = $pageLi->appendChild( $a );
		}

		//	categories
		$table = new Application_Category();
		$table = $table->select();

		$innerUl = $this->_html->createElement( 'ul' );
		$innerUl = $li->appendChild( $innerUl );
		$innerLi = $this->_html->createElement( 'li', 'Categories' );
		$innerLi = $innerUl->appendChild( $innerLi );

		$innerInnerUl = $this->_html->createElement( 'ul' );
		$innerInnerUl = $innerLi->appendChild( $innerInnerUl );
		foreach( $table as $data )
		{
			$url = $this->_xml->createElement( 'url' );
			$url = $urlset->appendChild( $url );
			$data['url'] = '' . Ayoola_Page::getHomePageUrl() . '/posts/' . $data['category_name'];
			$loc = $this->_xml->createElement( 'loc', $data['url'] );
			$loc = $url->appendChild( $loc );
			$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
			$changefreq = $url->appendChild( $changefreq );
			$defaultPriority = 5;
			$priority = $this->_xml->createElement( 'priority', '0.' . $defaultPriority );
			$priority = $url->appendChild( $priority );
				
			$a = $this->_html->createElement( 'a', htmlspecialchars( $data['category_label'] ) );
			$a->setAttribute( 'href', $data['url'] );
			$a->setAttribute( 'title', htmlspecialchars( $data['category_description'] ) );
				
			$pageLi = $this->_html->createElement( 'li' );
			$pageLi = $innerInnerUl->appendChild( $pageLi );
			$a = $pageLi->appendChild( $a );
		}

		// post types
		$table = new Application_Article_Type();
		$table = $table->select();

		$innerUl = $this->_html->createElement( 'ul' );
		$innerUl = $li->appendChild( $innerUl );
		$innerLi = $this->_html->createElement( 'li', 'Post Types' );
		$innerLi = $innerUl->appendChild( $innerLi );

		$innerInnerUl = $this->_html->createElement( 'ul' );
		$innerInnerUl = $innerLi->appendChild( $innerInnerUl );
		foreach( $table as $data )
		{
			$url = $this->_xml->createElement( 'url' );
			$url = $urlset->appendChild( $url );
			$data['url'] = '' . Ayoola_Page::getHomePageUrl() . '/posts/' . $data['post_type_id'];
			$loc = $this->_xml->createElement( 'loc', $data['url'] );
			$loc = $url->appendChild( $loc );
			$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
			$changefreq = $url->appendChild( $changefreq );
			$defaultPriority = 6;
			$priority = $this->_xml->createElement( 'priority', '0.' . $defaultPriority );
			$priority = $url->appendChild( $priority );
				
			$a = $this->_html->createElement( 'a', htmlspecialchars( $data['post_type'] ) );
			$a->setAttribute( 'href', $data['url'] );
			$a->setAttribute( 'title', $data['post_type'] );
				
			$pageLi = $this->_html->createElement( 'li' );
			$pageLi = $innerInnerUl->appendChild( $pageLi );
			$a = $pageLi->appendChild( $a );
		}

		//	profiles
		$table =  'Application_Profile_Table';
        $table = $table::getInstance( $table::SCOPE_PRIVATE );
        $table->getDatabase()->getAdapter()->setAccessibility( $table::SCOPE_PRIVATE );
        $table->getDatabase()->getAdapter()->setRelationship( $table::SCOPE_PRIVATE );
        $table = $table->select();

		$innerUl = $this->_html->createElement( 'ul' );
		$innerUl = $li->appendChild( $innerUl );
		$innerLi = $this->_html->createElement( 'li', 'Profiles' );
		$innerLi = $innerUl->appendChild( $innerLi );

		$innerInnerUl = $this->_html->createElement( 'ul' );
		$innerInnerUl = $innerLi->appendChild( $innerInnerUl );
		foreach( $table as $data )
		{
			$url = $this->_xml->createElement( 'url' );
			$url = $urlset->appendChild( $url );
			$data['url'] = '' . Ayoola_Page::getHomePageUrl() . '/' . $data['profile_url'];
			$loc = $this->_xml->createElement( 'loc', $data['url'] );
			$loc = $url->appendChild( $loc );
			$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
			$changefreq = $url->appendChild( $changefreq );
			$defaultPriority = 7;
			$priority = $this->_xml->createElement( 'priority', '0.' . $defaultPriority );
			$priority = $url->appendChild( $priority );
				
			$a = $this->_html->createElement( 'a', htmlspecialchars( $data['display_name'] ? : $data['url'] ) );
			$a->setAttribute( 'href', $data['url'] );
			$a->setAttribute( 'title', htmlspecialchars( $data['profile_description'] ) );
				
			$pageLi = $this->_html->createElement( 'li' );
			$pageLi = $innerInnerUl->appendChild( $pageLi );
			$a = $pageLi->appendChild( $a );
		}
		

		//	posts
		$table = new Application_Article_Table();
		$table = $table->select();

		$innerUl = $this->_html->createElement( 'ul' );
		$innerUl = $li->appendChild( $innerUl );
		$innerLi = $this->_html->createElement( 'li', 'Posts' );
		$innerLi = $innerUl->appendChild( $innerLi );

		$innerInnerUl = $this->_html->createElement( 'ul' );
		$innerInnerUl = $innerLi->appendChild( $innerInnerUl );
		foreach( $table as $data )
		{
		//	var_export( $data );
			if( 0 !== $data['auth_level'] )
			{
				continue;
			}
			$url = $this->_xml->createElement( 'url' );
			$url = $urlset->appendChild( $url );
			$data['url'] = '' . Ayoola_Page::getHomePageUrl() . '' . $data['article_url'];
			$loc = $this->_xml->createElement( 'loc', $data['url'] );
			$loc = $url->appendChild( $loc );
			$changefreq = $this->_xml->createElement( 'changefreq', 'always' );
			$changefreq = $url->appendChild( $changefreq );
			$defaultPriority = 9;  
			$priority = $this->_xml->createElement( 'priority', '0.' . $defaultPriority );
			$priority = $url->appendChild( $priority );
				
			$a = $this->_html->createElement( 'a', htmlspecialchars( $data['article_title'] ? : $data['url'] ) );
			$a->setAttribute( 'href', $data['url'] );
			$a->setAttribute( 'title', htmlspecialchars( $data['article_description'] ) );
				
			$pageLi = $this->_html->createElement( 'li' );
			$pageLi = $innerInnerUl->appendChild( $pageLi );
			$a = $pageLi->appendChild( $a );
		}

    } 
	// END OF CLASS
}
