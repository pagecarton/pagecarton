<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Application_Article_Type_Poll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Poll.php 5.11.2012 12.02am ayoola $
 */

/**
 * @see Application_Article_Type_Abstract  
 */
 
require_once 'Application/Article/Abstract.php';


/**
 * @category   PageCarton
 * @package    Application_Article_Type_Poll
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Application_Article_Type_Poll extends Application_Article_Type_Abstract
{
    /**
     * Using another layer of auth for this one
     *
     * @var boolean
     */
	protected static $_accessLevel = 0;
	
    /**
     * The method does the whole Class Process
     * 
     */
	protected function init()
    {
		$this->createForm( 'Vote', '' );
		$form = $this->getForm()->view();
	//	$values = $this->getForm()->getValues();
	//	var_export( $_POST );
		if( ! $values = $this->getForm()->getValues() )
		{ 
		//	var_export( 123 );
			//	show form
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
			$data['poll_form'] = $form;
			$this->setViewContent( $data['poll_form'] );
		//	return false; 
		}
		else
		{
			//	var_export( $values );
			//	cast vote
			$_GET['article_url'] = $values['article_url'];
			$data = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
	//			var_export( $data );
			
			//	Check if its a valid answer
			if( ! in_array( $values['poll_answer'], array_map( 'self::getOptionId', $data['poll_options'] ) ) ){ return false; }
			$data['poll_votes'] = @$data['poll_votes'] ? : array();
	//		$id = self::getOptionId( $values['poll_answer'] );
			$data['poll_votes'][$values['poll_answer']] = @$data['poll_votes'][$values['poll_answer']] ? : 0;
			$data['poll_votes'][$values['poll_answer']]++; 
			self::saveArticle( $data );
			
		}

		//	Display Graph
		$data['poll_graph'] = self::showGraph( $data );
		$this->setViewContent( $data['poll_graph'] );
		$this->setViewContent( self::__( '<p>' . $data['article_description'] . '</p>' ) );
		$this->_objectTemplateValues = array_merge( $data ? : array(), $this->_objectTemplateValues ? : array() );
	//	var_export( $data );
	//	var_export( $pollData );
    } 
	
    /**
     * Returns a unique id for an option
     * 
     */
	public static function getOptionId( $option )
    {
		return 'a' . md5( $option );
	}
    /**
     * The method displays the graph for the poll
     * 
     */
	public static function showGraph( array $data )
    {
	//	$id = self::getOptionId( $values['poll_answer'] );
		if( empty( $data['poll_votes'] ) ){ return; }
		$totalVotes = intval( array_sum( $data['poll_votes'] ? : array() ) ) + intval( array_sum( @$data['poll_option_preset_votes'] ? : array() ) );
		$colors = array( 'red', 'orange', 'yellow', 'green', 'blue', 'indigo', 'violet' );
		$html = null;
	//	$html .= '<ul style="list-style:none;">';
		foreach( $data['poll_options'] as $key => $each )
		{
			$id = self::getOptionId( $each ); 
			$color = array_shift( $colors ); //	Rotate colors
			array_push( $colors, $color ); //	Rotate colors
			$eachVote = @$data['poll_votes'][$id] + @$data['poll_option_preset_votes'][$key];
			$percentage = ( $eachVote / $totalVotes ) * 100;
			$html .= $each . ': (' . intval( $eachVote ) . ') <div style="background-color:' . $color . '; height:2.5em; width:' . $percentage . '%;text-align:center;"><span style="background-color:white;line-height:2.5em; text-align:center; vertical-align:center;"> ' . round( $percentage, 1 ) . '% </span></div>';  
		}
	//	$html .= '</ul>';
		return $html;
		//	var_export( $pollData );
    } 
	
    /**
     * Form to display poll
     * 
     */
	public function createForm( $submitValue = null, $legend = null, Array $values = null )
    {
		//	Form to create a new page
        $form = new Ayoola_Form( array( 'name' => $this->getObjectName() ) );
		$fieldset = new Ayoola_Form_Element;
		$fieldset->hashElementName = true;
		$form->submitValue = $submitValue ;
	//	$fieldset->placeholderInPlaceOfLabel = true;
		$pollData = $this->getParameter( 'data' ) ? : $this->getIdentifierData();
		$pollData['poll_options'] = is_array( $pollData['poll_options'] ) ? array_combine( array_map( 'self::getOptionId', $pollData['poll_options'] ), $pollData['poll_options'] ) : array();
	//	var_export( $pollData );
		
		//	Question
		$fieldset->addElement( array( 'name' => 'poll_answer', 'id' => 'poll_answer' . md5( @$pollData['article_url'] ), 'label' => @$pollData['poll_question'], 'type' => 'Radio', 'value' => @$values['poll_answer'] ), $pollData['poll_options'] );
		$fieldset->addElement( array( 'name' => 'article_url', 'type' => 'Hidden', 'value' => @$pollData['article_url'] ) );
	//	$fieldset->addRequirement( 'poll_answer', array( 'ArrayKeys' => $pollData['poll_options'] ) );
		$fieldset->addLegend( $legend );
		$form->addFieldset( $fieldset );
		$this->setForm( $form );

    } 
	
	// END OF CLASS
}
