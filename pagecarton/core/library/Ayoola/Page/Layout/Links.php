<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Links
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Pages.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Page_Layout_Abstract
 */

require_once 'Application/Subscription/Abstract.php';


/**
 * @category   PageCarton
 * @package    Ayoola_Page_Layout_Links
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Layout_Links extends Ayoola_Page_Layout_Abstract
{

    /**
     *
     *
     * @var string
     */
	protected static $_objectTitle = 'Theme Links';

    /**
     *
     *
     * @var string
     */
	protected static $_regex = '#(<a[^<>]*href[\s]*=[\s]*[\'"])([^\'"]*)([\'"][^<>]*>)(.*)(</a>)#isU';

    /**
     * The method does the whole Class Process
     *
     */
	protected function init()
    {
		try
		{
	//		var_export( $files );
			if( ! $data = $this->getIdentifierData() ){ return false; }

	//		var_export( $this->getMyFilename() );
			$filename = '/layout/' . $data['layout_name'] . '/theme/data_json';

			$path = Ayoola_Doc_Browser::getDocumentsDirectory() . $filename;
			$content = file_get_contents( $path );
			$contentArray = json_decode( $content, true );

	//		var_export( $contentArray );
			$htmlContent = array();
			$titles = array();
			$urls = array();
			foreach( $contentArray as $contentKey => $each )
			{
			//	var_export( $contentKey );
			//	var_export( $each );
				if( is_array( $each ) && ! empty( $each['codes'] ) )
				{
					$htmlContent[$contentKey] = $each['codes'];
				///	var_export( $each['editable'] );
				}
				elseif( is_array( $each ) && ! empty( $each['editable'] ) )
				{
					$htmlContent[$contentKey] = $each['editable'];
				///	var_export( $each['editable'] );
				}
				elseif( strip_tags( $each ) !== $each )
				{
					$htmlContent[$contentKey] = $each;
				}
				preg_match_all( static::$_regex, $htmlContent[$contentKey], $matches );
			//	var_export( $matches );
		//		var_export( $htmlContent );
				$matches[2] = array_combine( $matches[0], $matches[2] );
				$matches[4] = array_combine( $matches[0], $matches[4] );
				$urls += $matches[2];
				$titles += $matches[4];
			//	$tracket += $matches[2];
			}
	//		exit();
				asort( $urls );
		//		var_export( $urls );
		//		var_export( $titles );
			$linksData = array();
			$keyList = array();

			$form = new Ayoola_Form();
			$form->submitValue = 'Update';
	//		$fieldset = new Ayoola_Form_Element();
			$xml = array();
			$pages = Ayoola_Page::getAll( $data );
/* 			$pages = Ayoola_Page_Page::getInstance();
			$pages = $pages->select();
			require_once 'Ayoola/Filter/SelectListArray.php';
			$filter = new Ayoola_Filter_SelectListArray( 'url', 'url');
			$pages = $filter->filter( $pages );

			$pages += Ayoola_Page_Layout_Pages::getPages( $data['layout_name'], 'list-url' ) ? : array();
			asort( $pages );
 */
			$fieldset = new Ayoola_Form_Element();
			$fieldset->addElement( array( 'name' => 'editing-mode', 'label' => '', 'onchange' => 'ayoola.spotLight.splashScreen(); location.search += \'&editing-mode=\' + this.value; ', 'type' => 'Select', 'style' => 'width:100%', 'value' => @$_GET['editing-mode'] ), array( 'simple' => 'Links without codes (Simple Mode)', 'advanced' => 'All links (Advanced Mode)' ) );
			$form->addFieldset( $fieldset );

		//	Application_Article_Abstract::initHTMLEditor();

		//	var_export( $htmlContent );
/*			foreach( $htmlContent as $contentKey => $eachContent )
			{
				$content = $eachContent;
	//			$content = $this->getPreviousContent();

				// Instantiate the object
				$xml[$contentKey] = new Ayoola_Xml();

				// Build the DOM from the input (X)HTML snippet
				@$xml[$contentKey]->loadHTML( '<?xml encoding="utf-8" ?>' . $content );
				# remove <!DOCTYPE
				$xml[$contentKey]->removeChild( $xml[$contentKey]->doctype );

				# remove <html><body></body></html>
				$links = $xml[$contentKey]->getElementsByTagName( 'a' );
*/
			//	var_export( $_POST );
		//		foreach( $links as $each )
				$newTitles = $this->getGlobalValue( 'title' );
				$newUrls = $this->getGlobalValue( 'url' );
				$counter = 0;
				foreach( $urls as $urlKey => $each )
				{
/*					$title = trim( $each->nodeValue );
//					$title = trim( htmlentities( $each->nodeValue ) );
					$url = $each->getAttribute( 'href' );
			//		var_export( $url . "\r\n");
			//		var_export( $title . "\r\n");

			//		var_export( $each->childNodes->length );
					if( $each->childNodes->length > 0 )
					{
						$title = null;
						foreach( $each->childNodes as $childKey => $eachChild )
						{
							if( strtolower( $eachChild->tagName ) === 'img' && stripos( $eachChild->getAttribute( 'src' ), '/img/logo.png' ) === false )
							{
								continue 2;
							}
							if( ! $eachChild->nodeValue )
							{

								//	causing some elements to be missing. don't know why'
							//	$eachChild->nodeValue = " ";
							}
							$title .= $each->ownerDocument->exportHtml( $eachChild ) ? : $eachChild->nodeValue;
		//					var_export( $title );
						}
					}
*/
					$url = $each;
					//	change links with /page.html to /page
			//			var_export( $url );
					$url = self::themePageToUrl( $url, $data['layout_name'] );
		//			var_export( $title . "\r\n");
					$title = $titles[$urlKey];
					$title =  trim( $title, "\r\n\t " );
					$url =  trim( $url, "\r\n\t " );
					$title = str_ireplace( array( '<p>', '</img>', '</p>', "&nbsp;", "\r\n", "\t" ), '', $title );
					$title = preg_replace( '# +#', ' ', $title );
					$titleX = htmlentities( $title, null, 'utf-8' );
					$titleX = str_replace("&nbsp;", "", $titleX);
					$titleX = html_entity_decode($titleX);
					if( ! trim( $titleX ) )
					{
					//	continue;
					}
					if( strip_tags( $title ) != $title && @$_GET['editing-mode'] !== 'advanced' )
					{
						continue;
					}
				//	var_export( $newTitles[$counter] . "\r\n" );

					$key = md5( $title . $url );
					if( ! array_key_exists( $key, $keyList ) )
					{
						$linkValue = array( 'title' => $title, 'url' => $url, 'new_title' => $newTitles[$counter], 'new_url' => $newUrls[$counter] );
						$linksData[$urlKey] = $linkValue;
						$keyList[$key] = $linksData[$urlKey];
						$fieldset = new Ayoola_Form_Element();
						$fieldset->addElement( array( 'name' => 'title', 'label' => '', 'data-html' => true, 'placeholder' => $title, 'type' => 'InputText', 'multiple' => 'multiple', 'style' => 'width:100%', 'value' => trim( $title, "\r\n\t " ) ) );
						$fieldset->addElement( array( 'name' => 'url', 'label' => '', 'onchange' => 'if( this.value == \'\' ){ a = prompt( \'New Url\', \'/url\' ); if( ! a ) return false; var option = document.createElement( \'option\' ); option.text = a; option.value = a; this.add( option ); this.value = a;  }', 'placeholder' => $url, 'type' => 'Select', 'multiple' => 'multiple', 'style' => 'width:100%', 'value' => trim( $url ) ), array_unique( array( $url => $url ) + $pages + array( '' => 'Custom URL' ) ) );
						$fieldset->wrapper = 'white-background';
					//	var_export( $url );
						$counter++;
						$fieldset->addLegend( 'Link ' . $counter );
						$form->addFieldset( $fieldset );
					}
					else
					{
						$linksData[$urlKey] = $keyList[$key];
					}
				}
	//		}


		//	var_export( $linksData );
			if( $linksData )
			{
				$this->setViewContent( $form->view(), true );
				$this->setViewContent( '<div class="pc-notify-info">Please take caution while using this tool as it is still experimental. </div>' );
			}
			else
			{
				$this->setViewContent( '<div class="badnews">There are no editable links on this theme.</div>', true );
			}
			if( ! $values = $form->getValues() ){ return false; }
		//	var_export( $form->getValues() );
			$done = array();
/*			foreach( $linksData as $key => $each )
			{

				$thisValue = array( 'title' => $each['title'], 'url' => $each['url'] );
				$linkKey = md5( serialize( $thisValue ) );
				if( in_array( $thisValue, $done ) )
				{

				}
				else
				{
					$done[] = $thisValue;
					$currentTitle = array_shift( $values['title'] );
					$currentUrl = array_shift( $values['url'] );
					$delete = false;
					if( ! trim( $currentTitle ) && ! trim( $currentUrl ) )
					{
						//	delete link
						$delete = true;
					}
					if( ! trim( $currentTitle ) )
					{
						$currentTitle = $each['title'];
					}
					if( ! trim( $currentUrl ) )
					{
						$currentUrl = $each['url'];
					}
					$linkValue[$linkKey] = array( 'title' => $currentTitle, 'url' => $currentUrl, 'delete' => $delete );

			//		$linkValue[$linkKey]['title'] = htmlspecialchars_decode( $linkValue[$linkKey]['title'] );
					if( strip_tags( $linkValue[$linkKey]['title'] ) != $linkValue[$linkKey]['title'] )
					{

						$each['node']->nodeValue = null;
						$f = new Ayoola_Xml();
						$f->preserveWhiteSpace = FALSE;
				//		var_export( $linkValue[$linkKey]['title'] );
						$f->loadHtml( '<?xml encoding="utf-8" ?>' . trim( $linkValue[$linkKey]['title'] ) );
						# remove <!DOCTYPE
					//	$f->removeChild( $f->doctype );
				//		$f->replaceChild($f->firstChild, $f->firstChild);
						$newNode = $f->documentElement->firstChild;
				//		echo $linkValue[$linkKey]['title'];
				//		echo $newNode->c14N();
					}
					else
					{
					//	var_export( $linkValue[$linkKey]['title'] );
						$newNode = $each['node']->ownerDocument->createTextNode( $linkValue[$linkKey]['title'] );
					}
					$linkValue[$linkKey]['new_node'] = $newNode;
				}
				$each['node']->nodeValue = null;
			//	$newNode =
				if( empty( $linkValue[$linkKey]['delete'] ) )
				{
					$newNode = $each['node']->ownerDocument->importNode( $linkValue[$linkKey]['new_node'], true );
					$newNode ? $each['node']->appendChild( $newNode ) : null;
					$each['node']->setAttribute( 'href', $linkValue[$linkKey]['url'] );
					$class = $each['node']->getAttribute( 'class' );
					if( stripos( $class, 'scroll' ) !== false )
					{
						if( $linkValue[$linkKey]['url'][0] !== '#' )
						{
							$each['node']->setAttribute( 'class', str_ireplace( 'scroll', '', $class ) );
						}
					}
				}
				else
				{
					$nodeToDelete = $each['node'];
					if( strtoupper( $each['node']->parentNode->tagName ) === 'LI' )
					{
						$nodeToDelete = $each['node']->parentNode;
					}
					$nodeToDelete->parentNode->removeChild( $nodeToDelete );
				}
			}
*/	//		var_export( $values );

			foreach( $values as $contentKey => $eachContent )
			{

			}

			foreach( $htmlContent as $contentKey => $eachContent )
			{
          //  	$doc = str_ireplace( array( '<body>', '</body>', '<p->', '</p->' ), '', $xml[$contentKey]->exportHTML( $xml[$contentKey]->documentElement->firstChild ) );

		//		var_export( $doc );
				//	delete all paragraphs in anchor
				$callback = function( $matches ) use( $linksData )
				{
					if( isset( $linksData[$matches[0]] ) && ( $linksData[$matches[0]]['new_url'] !== $linksData[$matches[0]]['url'] ||  $linksData[$matches[0]]['new_title'] !== $linksData[$matches[0]]['title'] ) )
					{
						$info = $linksData[$matches[0]];
					//	var_export( $info );
						$new = preg_replace( static::$_regex, '$1' . $linksData[$matches[0]]['new_url'] . '$3' . $linksData[$matches[0]]['new_title'] . '$5', $matches[0] );
					//	$new = str_replace( $info );
					//	var_export( $new . "\r\n" );
						return $new;
					}
					else
					{
						return $matches[0];
					}
				};
				$doc = preg_replace_callback( static::$_regex, $callback, $eachContent );
			//	var_export( $doc );
		//		var_export( $contentArray[$contentKey] );

				$contentArray[$contentKey] = $doc;
			//	var_export( $contentArray[$contentKey] );
		//	var_export( $contentArray[$contentKey] );
			}
		//	exit();
			$newContent = json_encode( $contentArray );
		//	var_export( $contentArray );
			file_put_contents( $path, $newContent );
		//	$this->updateFile( array( 'plain_text' => $xml->saveHTML() ) );
			static::refreshThemePage( $data['layout_name'] );
			$this->setViewContent( '<p class="boxednews goodnews">Theme links saved successfully.</p>', true );

		//	echo $xml->view();
		//	exit();
		//	var_export( $linksData );
		//	var_export( $linkValue );
		}
		catch( Ayoola_Page_Layout_Exception $e ){ return false; }
    }
	// END OF CLASS
}
