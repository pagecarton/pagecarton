<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @HashTag   Ayoola
 * @package    Application_HashTag_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Abstract.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Application_HashTag_Exception 
 */
 
require_once 'Application/HashTag/Exception.php';


/**
 * @HashTag   Ayoola
 * @package    Application_HashTag_Abstract
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

abstract class Application_HashTag_Abstract extends Ayoola_Abstract_Table
{
	
    /**
     * Where to save hash tags
     *
     * @var array
     */
	protected static $_hashTags;
	
    /**
     * Whether class is playable or not
     *
     * @var boolean
     */
	protected static $_playable = true;
	
    /**
     * Access level for player
     *
     * @var boolean
     */
	protected static $_accessLevel = array( 99, 98 );
	
    /**
     * Identifier for the column to edit
     * 
     * @var array
     */
	protected $_identifierKeys = array( 'hashtag_id' );
	
    /**
     * 
     * @var string
     */
	protected $_tableClass = 'Application_HashTag';
	
    /**
     * Get Trending tags
     * 
     * param string Application Name
     * return boolean
     */
	public static function getTrending( $app )
    {
		$tags = self::get( $app );
	//	var_export( $tags );
		$tags = @$tags['tags'] ? : array();
	//	var_export( $tags );
		return $tags;
	}
	
    /**
     * Get all tags
     * 
     * param string Application Name
     * return boolean
     */
	public static function getAll( $app )
    {
		$tags = self::get( $app );
		$tags = isset( $tags['cue'], $tags['tags'] ) ? array_merge( $tags['cue'], $tags['tags'] ) : array();
	//	var_export( $tags );
		return $tags;
	}
	
    /**
     * Get tags
     * 
     * param string Application Name
     * return boolean
     */
	public static function get( $app = null )
    {
		if( ! empty( self::$_hashTags[$app]['hash_tags'] ) ){ return self::$_hashTags[$app]['hash_tags']; }
		$table = new Application_HashTag();
		$where = null;
		$method = 'select';
		if( $app )
		{
			$where = array( 'application_name' => $app );
			$method = 'selectOne';
		}
//		$response = $table->$method( null, $where );
		
		if( ! self::$_hashTags[$app] = $table->$method( null, $where ) )
		{
			return array();
		}
		
	//	var_export( $response );
	//	var_export( $app );
	//	var_export( self::$_hashTags[$app] );
		if( $app ){ return self::$_hashTags[$app]['hash_tags']; }
		return self::$_hashTags[$app]['hash_tags'];
	}
	
    /**
     * Set an hash tags
     * 
     * param mixed List of Tags
     * param string Application Name
     * return boolean
     */
	public static function set( $tags, $applicationName, $reference )
    {
		$tags = array_map( 'trim', explode( ',', $tags ) );
		$table = new Application_HashTag();
		$where = array( 'application_name' => $applicationName );
		$appInfo = $table->selectOne( null, $where );
	//	$hashTags = Application_HashTag_Settings::getSettings( 'hashTags' );
		$maxTags = 10;
		$maxTagCues = 20;
		$noRefreshTagDeadline = 10000; //	seconds
		$noRefreshCueDeadline = 10000; //	seconds
		$previousTags = @$appInfo['hash_tags'] ? : array();
		$previousTags['tags'] = @$previousTags['tags'] ? : array();
		$previousTags['cue'] = @$previousTags['cue'] ? : array();
		$newTags = $previousTags;
		$referenceInfo = array( 'time' => time() );
		
		//	Shuffle tags
		foreach( $previousTags['tags'] as $oldEach => $eachReferences )
		{
	//		var_export( $oldEach );
	//		var_export( $eachReferences );
			if( ! is_array( $eachReferences ) ){ continue; }
			//	timer
			$eachReferences = end( $eachReferences );
			if( time() - $eachReferences['time'] > $noRefreshTagDeadline )
			{
				//	remove
				unset( $newTags['tags'][$oldEach] );
				
				//	Move another UP from cue
				$cue = reset( $newTags['cue'] );
				$newTags['tags'][key( $newTags['cue'] )] = reset( $newTags['cue'] );
				
				//	remove
				unset( $newTags['tags'][$oldEach] );
			}
			
		}
		
		//	Shuffle cue
		foreach( $previousTags['cue'] as $oldEach => $eachReferences )
		{
			//	timer
			$eachReferences = end( $eachReferences );
			if( time() - $eachReferences['time'] > $noRefreshCueDeadline )
			{
				unset( $newTags['cue'][$oldEach] );
			}
		}
		
		//	find out if lowest in tags is lesser than the highest in the group
		$testTags = $previousTags;
		if( $testTags['tags'] )
		do
		{
 			$tag = count( end( $testTags['tags'] ) );
			$cue = count( current( $testTags['cue'] ) );
			if( $tag > $cue ){ break; }
			$newTags['tags'][key( $testTags['cue'] )] = current( $testTags['cue'] );
			$newTags['cue'][key( $testTags['tags'] )] = end( $testTags['tags'] );
			unset( $newTags['tags'][key( $testTags['tags'] )] );
			unset( $newTags['cue'][key( $testTags['cue'] )] );
			$tag = array_pop( $testTags['tags'] );
			$cue = array_pop( $testTags['cue'] );
		}
		while( $tag < $cue );
		
		//	insert tags
		foreach( $tags as $each )
		{
			$filter = new Ayoola_Filter_Name();
			$filter->replace = '-';
			$each = $filter->filter( strtolower( $each ) );
			if( ! $each ){ continue; }
			do 
			{
				if
				( 
					//	Our new tag list is not full
					( count( $newTags['tags'] ) < $maxTags )
					//	we are already in the tag list, we need to update
				||  ! empty( $newTags['tags'][$each] ) 
				)
				{
					$newTags['tags'][$each] = isset( $newTags['tags'][$each] ) ? $newTags['tags'][$each] : array();
					$newTags['tags'][$each][$reference] = $referenceInfo;
					
					//	Remove it from the cue
					unset( $newTags['cue'][$each][$reference] );
				//	$newTags['tags'][$each][] = $referenceInfo + array( 'tag' => $each );
/* 					$newTags['tags'][$each]['counter']++;
					$newTags['tags'][$each]['time'] = time();
 */				//	@var_export( ++$i );
					break;
				}
				elseif
				(
					//	Our new tag list is not full
					( count( $newTags['cue'] ) < $maxTags )
					//	we are already in the tag list, we need to update
				||  ! empty( $newTags['cue'][$each] ) 
				)
				{
					$newTags['cue'][$each] = isset( $newTags['cue'][$each] ) ? $newTags['cue'][$each] : array();
					$newTags['cue'][$each][$reference] = $referenceInfo;
					
					//	Remove it from the cue
					unset( $newTags['tags'][$each][$reference] );
/* 					$newTags['cue'][$each]['counter']++;
					$newTags['cue'][$each]['time'] = time();
 */					break;
				}
			}
			while( false );
		}
		asort( $newTags['tags'] );
		asort( $newTags['cue'] );
	//	$method = $appInfo ? 'update' : 'insert';
	//	var_export( $newTags );
		if( $appInfo )
		{
			$table->update( array( 'hash_tags' => $newTags ), $where );
		}
		else
		{
			$table->insert( array( 'hash_tags' => $newTags ) + $where );
		}

	} 
	// END OF CLASS
}
