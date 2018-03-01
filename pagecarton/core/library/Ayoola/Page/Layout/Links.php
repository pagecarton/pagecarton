<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
			foreach( $contentArray as $contentKey => $each )
			{
				if( is_array( $each ) && ! empty( $each['editable'] ) )
				{
					$htmlContent[$contentKey] = $each['editable'];
				///	var_export( $each['editable'] );
				}
				elseif( strip_tags( $each ) !== $each )
				{
					$htmlContent[$contentKey] = $each;
				}

			}
	//		exit();

		//		var_export( $htmlContent ); 
			$linksData = array(); 		
			$keyList = array(); 		

			$form = new Ayoola_Form();
			$form->submitValue = 'Update';
	//		$fieldset = new Ayoola_Form_Element();
			$xml = array();
		//	var_export( $htmlContent );
			foreach( $htmlContent as $contentKey => $eachContent )
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
			//	$xml[$contentKey]->replaceChild($xml[$contentKey]->firstChild->firstChild->firstChild, $xml[$contentKey]->firstChild);
				$links = $xml[$contentKey]->getElementsByTagName( 'a' );

			//	var_export( $_POST );
				foreach( $links as $each )
				{
					$fieldset = new Ayoola_Form_Element();
					$title = trim( $each->nodeValue );
//					$title = trim( htmlentities( $each->nodeValue ) );

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
								$eachChild->nodeValue = " ";
							}
							$title .= $each->ownerDocument->exportHtml( $eachChild ) ? : $eachChild->nodeValue;
		//					var_export( $title );
						}
					//	var_export( $title );
					//	$title = implode( array_map( array( $each->ownerDocument,"saveXML" ), iterator_to_array( $each->childNodes ) ) );
					//	$title = htmlspecialchars( $title );
					//		var_export( $title );
					}
					$url = $each->getAttribute( 'href' );
				//	var_export( $url );
		//			if( ! self::isThemePage( $url, $data['layout_name'] ) )
					{
				//		continue;
					}


					//	change links with /page.html to /page
					$url = self::themePageToUrl( $url, $data['layout_name'] );
				//		var_export( $url );
					$title = str_ireplace( array( '<p>', '</img>', '</p>', "&nbsp;", '' ), '', $title );
					$titleX = htmlentities($title, null, 'utf-8');
					$titleX = str_replace("&nbsp;", "", $titleX);
					$titleX = html_entity_decode($titleX);
		//			$title = preg_replace( '|^[^a-Z0-9]|', '', $title );
					if( stripos( $titleX, '<img' ) !== false )
					{
						//	we need to allow logo from getting here
					///	var_export( $innerHtml );
					//	continue;
					}
					if( ! trim( $titleX ) )
					{
					///	var_export( $innerHtml );
						continue;
					}
				//	var_export( $title );
					$linkValue = array( 'title' => $title, 'url' => $url, 'node' => $each );
					$key = md5( serialize( $linkValue ) );
					$linksData[] = $linkValue;
					if( ! array_key_exists( $key, $keyList ) )
					{
						$keyList[$key] = $linkValue;
						$fieldset->addElement( array( 'name' => 'title', 'label' => '', 'placeholder' => $title, 'type' => 'InputText', 'multiple' => 'multiple', 'style' => 'max-width:40%', 'value' => $title ) );
						$fieldset->addElement( array( 'name' => 'url', 'label' => '', 'placeholder' => $url, 'type' => 'InputText', 'multiple' => 'multiple', 'style' => 'max-width:40%', 'value' => $url ) );
						$form->addFieldset( $fieldset );
					}
				}
			}

		//	var_export( $linksData );
			if( $linksData )
			{
				$this->setViewContent( $form->view(), true ); 
				$this->setViewContent( '<div class="pc-notify-info">Please note that this tool is still experimental. </div>' ); 
			}
			else
			{
				$this->setViewContent( '<div class="badnews">There are no editable links on this theme.</div>', true ); 
			}
			if( ! $values = $form->getValues() ){ return false; }
		//	var_export( $form->getValues() );
			$done = array();
			foreach( $linksData as $key => $each )
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
					if( ! trim( $currentTitle ) )
					{
						$currentTitle = $each['title'];
					}
					if( ! trim( $currentUrl ) )
					{
						$currentUrl = $each['url'];
					}
					$linkValue[$linkKey] = array( 'title' => $currentTitle, 'url' => $currentUrl );

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
				$newNode = $each['node']->ownerDocument->importNode( $linkValue[$linkKey]['new_node'], true );
				$each['node']->appendChild( $newNode );			
				$each['node']->setAttribute( 'href', $linkValue[$linkKey]['url'] );
			}
	//		var_export( $contentArray );
			foreach( $htmlContent as $contentKey => $eachContent )
			{
            	$doc = str_ireplace( array( '<body>', '</body>', '<p->', '</p->' ), '', $xml[$contentKey]->exportHTML( $xml[$contentKey]->documentElement->firstChild ) );
				
		//		var_export( $doc );
				//	delete all paragraphs in anchor
				$doc = preg_replace( '#(\<a .*\>)(\<p\>)(.*)(\<\/p\>)(\<\/a\>)#', '$1$3$5', $doc );
			//	var_export( $doc );
		//		var_export( $contentArray[$contentKey] );
				
				$contentArray[$contentKey] = $doc;
			//	var_export( $contentArray[$contentKey] );
		//	var_export( $contentArray[$contentKey] );
			}
		//	exit();
			$newContent = json_encode( $contentArray );
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
