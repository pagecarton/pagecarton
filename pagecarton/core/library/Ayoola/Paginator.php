<?php

/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Paginator
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt    
 * @version    $Id: Paginator.php date time ayoola $
 */

/**
 * @see Ayoola_
 */
 
//require_once 'Ayoola/.php';  


/**
 * @category   PageCarton
 * @package    Ayoola_Paginator
 * @copyright  Copyright (c) 2011 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Paginator extends Ayoola_Abstract_Table
{
    /**
     * Multidimensional array of data to paginate
     *
     * @var array
     */
	protected $_data = null;

    /**
     * Msg to display if no record to display
     *
     * @var string
     */
	protected $_noRecordMessage = 'There are no rows to display or no record available.';

    /**
     * Title of List, displayed as an header
     *
     * @var string
     */
	public $listTitle;

    /**
     * Where the data for a row is stored
     *
     * @var string
     */
	public $rowDataColumn;

    /**
     *
     * @var null
     */
	public $noRowClass;
	public $deleteClass;
	
	public $hideCheckbox = false;
	public $hideNumbering = true;

    /**
     * Chunks of array split into diff pages
     *
     * @var array
     */
	protected $_pages = null;
	
    /**
     * The present page
     *
     * @var int
     */
	protected $_currentPage = 0;
	
    /**
     * The next page
     *
     * @var int
     */
	protected $_nextPage;
	
    /**
     * The previous page
     *
     * @var int
     */
	protected $_previousPage;
    /**
     * The last page
     *
     * @var int
     */
	protected $_lastPage;
	
    /**
     * The first page
     *
     * @var int
     */
	protected $_firstPage;
	
    /**
     * The representation of first, last, etc pages
     *
     * @var array
     */
	protected $_rep = array( 
							'first' => '<<',
							'last' => '>>',
							'next' => '>',
							'current' => 'Present',
							'previous' => '<'
							);
		
    /**
     * total of records
     *
     * @var int
     */
	protected $_noOfRecords = null;
	
    /**
     * Records on the present page
     *
     * @var int
     */
	protected $_noOfPageRecords = null;
		
    /**
     * The arithmetic total of pages
     *
     * @var int
     */
	protected $_noOfPages = null;
	
    /**
     * The no of records per page
     *
     * @var int
     */
	protected $_noPerPage = 10;
	
    /**
     * Rows of Data for the present Page
     *
     * @var array
     */
	protected $_rows = null;
	
    /**
     * List is outputed as a form object
     * So this holds the form obj 
     * @see Ayoola_Form
     * @var Ayoola_Form
     */
	protected $_list = null;

    /**
     * List options for links purposes
     *
     * @var array
     */
	protected $_listOptions = array();

    /**
     * Row options for links purposes
     *
     * @var array
     */
	protected $_rowOptions = array();

    /**
     * primary key used for customized link 
     *
     * @var string
     */
	protected $_key;
	
    /**
     * Page name used as Id of the list
     *
     * @var string
     */
	public $pageName = __CLASS__;
	
    /**
     * primary key used for customized link 
     *
     * @var string
     */
	public $formMethod = 'get'; 
	
    /**
     * The pagination Mark-Up
     *
     * @var string
     */
	protected $_pagination = null;
	
    /**
     * Set to false not to show Pagination Info
     *
     * @var boolean
     */
	public $showPagination = true;
	
    /**
     *
     * @var boolean
     */
	public $showSearchBox = false;

    /**
     * Constructor
     *
     * @param array of data to be Paginated.
     * 
     */
    public function __construct( $data = null )
    {
		self::$_counter++;
		$data ? $this->setData( $data ) : null;
    }
	
    /**
     * This method sets the $_data property to a value
     *
     * @param mixed
     * @return 
     */
    public function setData( $data )
    {
 	//	var_export( count( $data ) );
		@$this->_sortColumn = $this->getParameter( 'sort_column' ) ? : $this->_sortColumn;
		@$this->_sortColumn = ( $_REQUEST['pc_sort_column'] ) ? $_REQUEST['pc_sort_column'] : $this->_sortColumn;
		if( $this->_sortColumn )
		{
			$data = self::sortMultiDimensionalArray( $data, $this->_sortColumn );
		}
		if( @$_REQUEST['pc_sort_order_inverse'] )
		{
			krsort( $data );  
		}
		is_array( $data ) ? $this->_data = $data : null;
		$this->setPages();
		$this->setPagination();
    } 
	
    /**
     * This method returns the $_data property 
     *
     * @param void
     * @return array
     */
    public function getData()
    {
        return (array) $this->_data;
    } 
	
    /**
     * Returns the markup for pagination details
     *
     * @param 
     * @return string HTML
     */
    public function getPagination()
    {
		if( ! $this->_pagination )
		{
			$this->setPagination();
		}
        return $this->_pagination;
    } 
	
    /**
     * Sets the pagination property to as value
     *
     * @param 
     * @return string HTML of pagination details
     */
    public function setPagination()
    {
		if( ! $this->getRows() )
		{
			return null;
		}
		
		// Set representations to null if they are null
        $current = $this->_currentPage;
/*      
		$first = is_null( $this->_firstPage ) ? NULL : $this->_rep['first'];
		$last = is_null( $this->_lastPage ) ? NULL : $this->_rep['last'];
		$next = is_null( $this->_nextPage ) ? NULL : $this->_rep['next'];
		$previous = is_null( $this->_previousPage ) ? NULL : $this->_rep['previous'];

 */     
 		$first = $this->_rep['first'];
		$last = $this->_rep['last'];
		$next = $this->_rep['next'];
		$previous = $this->_rep['previous'];
		$classPlayer = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/' . $this->pageName . '/';
		$html = null;
		$options = range( 100, 500, 100 );
		$options = array_combine( $options, $options );
	//	$html = str_ireplace( '{{{---@@@BADNEWS@@@---}}}', '', $html );
	//	$html .='<p>Showing ' . $this->_noOfPageRecords . ' out of ' . $this->_noOfRecords . ' Record(s). Show ' . $noToShow .'</p>';
		require_once 'Ayoola/Page.php';
		$html .='<table class="pc-table">
					<tr>';
		$html .= is_null( $this->_firstPage ) ? NULL : ( '<td>
							<a name="paginator_navigation" class="pc-btn" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_firstPage . '/;changeElementId=' . $this->pageName . '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_firstPage ) ) . '">' . $first. '
							</a>
						</td>' );
		$html .= is_null( $this->_previousPage ) ? NULL : ( '<td>
							<a name="paginator_navigation" class="pc-btn" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_previousPage . '/;changeElementId=' . $this->pageName . '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_previousPage ) ) . '">' . $previous. '
							</a>
						</td>' );
		if( $this->_lastPage )
		{
			$html .= 		'<td>';
					
			//	Numbering of page
			for ($i = 0; $i <= $this->_lastPage; $i++) 
			{
				// make sure only 14 numbers are shown
				if( $i < $this->_currentPage - 5 )
				continue;
				if( $i > $this->_currentPage + 7 )
				continue;
				
				$label = $i + 1;
				if( $i === $this->_currentPage )
				{
					$html .= 		'<span name="paginator_navigation" class="pc-btn"> ' . $label . ' 
								</span>  ';
				}
				else
				{
		//			$html .= ' 		<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" rel="classPlayerUrl=' . $classPlayer . 'page/' . $i . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $i ) ) . '">' . $label . '</a> ';
					$html .= ' 		<a name="paginator_navigation" class="pc-btn" rel="" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $i ) ) . '">' . $label . '</a> ';  
				}
			}
			
			$html .= 		'</td>';
		}
		$html .= is_null( $this->_nextPage ) ? NULL :		'<td style="text-align:right;" >
							<a name="paginator_navigation" class="pc-btn" style="text-align:right;" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_nextPage . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_nextPage ) ) . '">' . $next. '</a></td>';
		$html .= is_null( $this->_lastPage ) ? NULL :		'<td style="text-align:right;" >
							<a name="paginator_navigation" class="pc-btn" style="text-align:right;" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_lastPage . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_lastPage ) ) . '">' . $last. '</a>
						</td>';
		$html .=	'</tr>
				</table>';
		$this->_pagination = $html;
    } 
	
    /**
     * Set Page separated into chunks of No of Pages
     *
     * @param void
     * @return mixed
     */
    public function setPages()
    {
		if( isset( $_GET['noPerPage'] ) && is_numeric( $_GET['noPerPage'] ) && $_GET['noPerPage'] !== @$_COOKIE['noPerPage'] )
		{
			setcookie( 'noPerPage', $_GET['noPerPage'], time() + (10 * 365 * 24 * 60 * 60), '/', null, false, true ); // Sets the Number of records per page as a cookie
			$this->_noPerPage = (int) $_GET['noPerPage'];
		}
		elseif( isset( $_COOKIE['noPerPage'] ) )
		{
			$this->_noPerPage = (int) $_COOKIE['noPerPage'];
		}
		if( isset( $_GET['page'] ) && is_numeric( $_GET['page'] ) )
		{
			$this->_currentPage = (int) $_GET['page'];
		}
			
		//	Validate present page  
		$this->_currentPage = $this->_currentPage < 0 ? 0 : $this->_currentPage;
		//	Validate number per page 
//+..		$this->_noPerPage = ( $this->_noPerPage > 0 && $this->_noPerPage < 501 ) ? $this->_noPerPage : 10;
		
	//	var_export( count( $this->getData() ) );
		$this->_pages = array_chunk( $this->getData(), $this->_noPerPage, true ); // Break Data up into pages
		$this->_noOfRecords = count( $this->getData() ); 
		
		$this->_firstPage = 0; // Start counting from 0
		$this->_lastPage = count( $this->_pages ) -1; // Deducting one since our page will start from 0
		$this->_noOfPages = count( $this->_pages ); // The real page count
		
		//	Validate present page again 
		$this->_currentPage = ( $this->_currentPage > $this->_lastPage ) ? $this->_lastPage : $this->_currentPage;
		$this->_currentPage = ( $this->_currentPage < $this->_firstPage ) ? $this->_firstPage : $this->_currentPage;
		
		$this->_nextPage = ( $this->_currentPage === $this->_lastPage ) ? NULL : $this->_currentPage + 1;
		$this->_previousPage = ( $this->_currentPage === $this->_firstPage ) ? NULL : $this->_currentPage - 1; 
		
		$this->_firstPage = ( $this->_currentPage === $this->_firstPage ) ? NULL : $this->_firstPage; 
		$this->_lastPage = ( $this->_currentPage === $this->_lastPage ) ? NULL : $this->_lastPage; 
		
				
    } 

    /**
     * Set Page separated into chunks of No of Pages
     *
     * @param void
     * @return array
     */
    public function getPages( $page = null )
    {
		if( null === $this->_pages )
		{
			$this->setPages();
		}
        return array_key_exists( $page, $this->_pages ) ? $this->_pages[$page] : $this->_pages;
    } 

    /**
     * Creates the list 
     * The parameter contains the field items the table will contain
     * e.g page_id => <a href="http://example.com/edit/%KEY%/">%FIELD%</a>
		- where that represent a column on the db table and it is present in the array sent to the paginator
     * e.g. delete => <a href="http://example.com/delete/%KEY%/">Delete</a>
     * The placeholders -%KEY% and %FIELD% are replaced with the primary key and the field value respectively.
     * 
     * @param array or will be converted internally
     * @return $this
     */
    public function createList( $fields = null )
    {	
		
		$fields = _Array( $fields );
		$this->fields = $fields;
		if( ! $this->getRows() ){ return $this->_list = $this->getNoRecordMessage(); }
		$key = $this->getKey() ? : null;
	//	var_export( $key );
		$bg = '#eeeeee';
		$html = '<table  class="pc-table">';
		if( ! @$this->noHeader )
		{
			$html .='<tr bgcolor="' . $bg . '">';
			$html .= @$this->hideCheckbox ? null : '<th><input title="toggle all" class="pc-paginator-record-selector-' . $this->pageName . self::$_counter . '" type="checkbox" onchange="var a = document.getElementsByClassName( this.className ); ayoola.div.toggleCheckboxes( a );ayoola.div.getParentWithClass( this, \'pc-form\' ).getElementsByClassName( \'pc-paginator-multioption-area\' )[0].style.display =\'block\';" /></th>';
		//	var_export( $this->hideNumbering );
			$html .= @$this->hideNumbering ? null : '<th>ID</th>';
			foreach( $fields as $field => $value )
			{
			//	self::v( $field );
				require_once 'Ayoola/Filter/UnderscoreToSpace.php';
				$filter = new Ayoola_Filter_UnderscoreToSpace;
				$head = ( ! is_numeric( $field ) ) ? $field : null ;
				$head = ( is_array( $value ) && ! empty( $value['header'] ) ? $value['header'] : $head ) ;
				$head = $filter->filter( $head );
				$field = ( ! empty( $value['field'] ) ? $value['field'] : $field );
				if( trim( $head ) )
				{
					$head = self::__( $head );
					self::filterReplacement( $head );
					$html .='<th>' . $head . ' <a href="javascript:;" onClick="window.location.search = window.location.search + \'&pc_sort_column=\' + \'' . ( $field ) . '\';" > &#8645; </a></th>';
				}
				else
				{
					$html .='<th></th>';
				}
			}
			if( $this->getRowOptions()  && ! @$this->noOptionsColumn )
			{
				$html .='<th></th>';
			}
			$html .='</tr>';
		}
		$allRows = $this->getRows();
		foreach( $allRows as $counter => $row )
		{
			$row = is_scalar( $row ) ? array( $this->_key => $row ) : $row;
			$row = $this->rowDataColumn ? $row[$this->rowDataColumn] : $row;
			
			$columnSearch = array();
			$columnReplace = array();
			if( @$this->crossColumnFields )
			{
				foreach( $row as $eachKey => $eachValue )
				{
					$columnSearch[] = '{{{%' . $eachKey . '%}}}';
					$columnReplace[] = $eachValue;
				}
			}
		//	var_export( $columnSearch );
		//	var_export( $columnReplace );
			
		//	$bg = $bg == '#ffffff' ? '#eeeeee' : '#ffffff';
			$rowClass = @$rowClass == 'pc-table-row1' ? 'pc-table-row2' : 'pc-table-row1';
			if( $this->noRowClass )
			{
				$rowClass = null;
			}
			$records = '<tr class="' . $rowClass . '">';    
//			$records = '<tr style="background-color:' . $bg . '; color:#000;">';    
			$records .= @$this->hideCheckbox ? null : '<td><input class="pc-paginator-record-selector-' . $this->pageName . self::$_counter . '" type="checkbox" name="' . $key . '[]" value="' . $row[$key] . '" onchange="ayoola.div.getParentWithClass( this, \'pc-form\' ).getElementsByClassName( \'pc-paginator-multioption-area\' )[0].style.display =\'block\'; " ></td>';
			$records .= @$this->hideNumbering ? null : '<td>'. ++$counter . '</td>';
//		var_export( $row );  
			$optionsHtml = null;
			foreach( $this->getRowOptions() as $option )
			{
				$option = str_replace( array( '%KEY%', '%FIELD%' ), array( $row[$key], '' ), $option );
				$option = str_replace( $columnSearch, $columnReplace, $option );
				$optionsHtml .= '<span style="" class=""> ' . $option . ' </span> ';			
			}
		//	var_export( $fields );
			foreach( $fields as $field => $value )
			{
				$rawFieldValues  = is_array( $value ) ? $value : array();
			//	if( is_array( $row ) && array_key_exists( $field, $row ) )
			//	var_export( $value );
				if( is_array( $value ) && ( ! empty( $value['value'] ) || ! empty( $value['filter'] ) ) )
				{
					if( ! empty( $value['field'] ) )
					{
						$field = $value['field'];
					}
					$value['value'] = @$value['value'] ? : $row[$field];
			//		if( ! empty( $value['filter'] ) && $value['filter'] implements Ayoola_Filter_Interface )
				//	if( ! Ayoola_Loader::loadClass( $options ) )
					{
				//		return false;
					}
					if( ! empty( $value['filter'] ) && Ayoola_Loader::loadClass( $value['filter'] ) )
					{
						$filter = new $value['filter'];
						if( ! empty( $value['filter_autofill'] ) )
						{
							$filter->autofil( $value['filter_autofill'] );
						}
					//	var_export( $row[$field] );
						$row[$field] = $filter->filter( $row[$field] );
					//	var_export( $row[$field] );
					}
					$value = $value['value'];

				}
			//	self::filterReplacement( $value );
		//		var_export( $value );
				$value = self::__( $value );    
				if( array_key_exists( $field, $row ) )
				{
					
				//	var_export( $value );
					// make adequate  replacement if required 
					self::filterReplacement( $row[$field] );
					$value =  $value ? : $row[$field];
 					if( is_array( $value ) )
					{ 
					//	var_export( $value );
						$value = print_r( $value, true ); 
					}
					elseif( is_object( $value ) )
					{ 
					//	var_export( (array) $value );
						$value = print_r( (array) $value, true ); 
					}
 					elseif( is_array( $row[$field] ) )
					{ 
					//	var_export( $value );
						$row[$field] = print_r( $row[$field], true ); 
					}
					elseif( is_object( $row[$field] ) )
					{ 
					//	var_export( (array) $value );
						$row[$field] = print_r( (array) $row[$field], true ); 
					}
				//	$value = str_replace( '%FIELD%', is_scalar( $row[$field] ) ? $row[$field] : null, $value );
					$value = str_replace( '%FIELD%', is_scalar( $row[$field] ) ? $row[$field] : null, is_scalar( $value ) ? $value : null );
					if( isset( $rawFieldValues['value_representation'][$value] ) )
					{
						$value = $rawFieldValues['value_representation'][$value];
					}
					elseif( isset( $rawFieldValues['value_representation']['pc_paginator_default'] ) )
					{
						$value = $rawFieldValues['value_representation']['pc_paginator_default'];
					}
					elseif( isset( $rawFieldValues['value_representation'] ) )
					{
						$value = null;
					}
					$value = str_replace( '%KEY%', @$row[$key], $value );
					$value = str_replace( '%PC-TABLES-ROW-OPTIONS%', $optionsHtml, $value );
					$value = str_replace( $columnSearch, $columnReplace, $value );
				//	var_export( $value );
			//		if( $this->crossColumnFields )

					//	we want to include html here // use personal filters for this
				//	$value = htmlentities( $value );
					$records .='<td>' . $value . '</td>';    
				}
				else
				{
					// I made this to allow for links like delete, edit, etc
					$value = str_replace( array( '%KEY%', '%FIELD%' ), array( $row[$key], '' ), $value );
					$value = str_replace( $columnSearch, $columnReplace, $value );
					//	var_export( $value );

					$records .='<td> ' . $value . '</td>';
				}
/*				
				if( ! empty( $value['options'] )  )
				{
					// I made this to allow for links like delete, edit, etc
				//	$value = str_replace( array( '%KEY%', '%FIELD%' ), array( $row[$key], '' ), $value );
					$records .='<td> Options </td>';
				}
*/				
			}
			if( $this->getRowOptions() && ! @$this->noOptionsColumn )
			{
				$records .='<td style="text-align:center;"><a onclick="var a = this.parentNode.parentNode.nextElementSibling; a.style.display = ( a.style.display == \'none\' ) ? \'\'  : \'none\';" href="javascript:"> options </a></td>';
			}
			$records .= '</tr>';
			if( $this->getRowOptions() )
			{
		//		var_export( $this->getRowOptions() );
				$records .= '<tr class="' . $rowClass . ' pc-btn-parent pc-btn-small-parent" style="display:none;"><td style="text-align:right;" colspan="100">';
				$records .= $optionsHtml;
				$records .= '</td></tr>';
			}
			$html .= $records;
		}
		//	leaves this so download can work
//		$this->_rows = array(); // Free up the memory
		$html .='</table>';		
		//						var a = ayoola.div.getParentWithClass( target, \'DragBox\' );
		Application_Javascript::addCode( 
			'
			pc_MultiDoForPaginationRecord = function( target, url )
			{
			//	var target = ayoola.events.getTarget( e );
				var a = ayoola.div.getParentWithClass( target, \'pc-form\' );
				if( a.className != \'pc-form\' )
				{
					return false;
				}
				var b =	ayoola.div.getFormValues( { form: a, dontDisable: true } );
			//	alert( location.search );

				ayoola.spotLight.showLinkInIFrame( url + ( location.search ? location.search : "?" ) + "&list_name=" + a.name + "&" + b, a.name );
			}
			'
		);

		$multiDoOption = array();
		$deletePrefix = null;
		if( $this->deleteClass )
		{
			$deletePrefix = 'delete_class/' . ( $this->deleteClass );
		}
		$multiDoOption[] = array( 
									'url' => Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Pagination_MultiDo_Delete/' . ( $deletePrefix ) . '',
									'name' => 'Delete',
									);

		$html .='<div class="pc-btn-parent pc-paginator-multioption-area" style="background-color:#d4d4d4;display:none;">';		
		foreach( $multiDoOption as $doOption )
		{
			$html .='<a class="" onclick="pc_MultiDoForPaginationRecord( this, \'' . $doOption['url'] . '\' );" href="javascript:">' . $doOption['name'] . '</a>';		
		}
		$html .='</div>';		


		
		// Embed the html in a form so as to service the checkboxes
		//	I am going to be using Ayoola_Form 
		//	I will need to create a method to implement adding html to Ayoola_Form ummmmmmmmm
		// wow, I only needed to add a new method addHtml to Ayoola_Form_Element. THANK GOD!!
		
		require_once 'Ayoola/Form.php';
		require_once 'Ayoola/Form/Element.php';
		$form = new Ayoola_Form( array( 'method' => $this->formMethod, 'name' => $this->pageName, 'class' => 'pc-form' ) );
		$element = new Ayoola_Form_Element;
		
		$formValue = array();
		$formValue['html'] = $html;
		
		$element->addElement( 'name=>'. $key .'[] :: type=>Html', $formValue );
		$element->addFilters( 'Int' );
		
		$form->addFieldset( $element );
		return $this->_list = $form;
		
    }

    /**
     * returns the html of list 
     *
     * @param void
     * @return string
     */
    public function getList()
    {	
		if( $this->_list instanceof Ayoola_Form )
		{
			return $this->_list->getForm();
		}
		return $this->_list;
    }

    /**
     * Return _listOptions List options
     *
     * @param void
     * @return array
     */
    public function getListOptions()
    {	
		return is_array( $this->_listOptions ) ? $this->_listOptions : array();
    }

    /**
     * Set an option for _listOptions List options
     *
     * @param mixed
     * @return null
     */
    public function setListOptions( $option )
    {	
		is_array( $option ) ? $this->_listOptions = array_merge( $this->_listOptions, $option ) : $this->_listOptions[] = $option;
    }

    /**
     * Return _rowOptions row options
     *
     * @param void
     * @return array
     */
    public function getRowOptions()
    {	
		return is_array( $this->_rowOptions ) ? $this->_rowOptions : array();
    }

    /**
     * Set an option for _rowOptions row options
     *
     * @param mixed
     * @return null
     */
    public function setRowOptions( $option )
    {	
		is_array( $option ) ? $this->_rowOptions = array_merge( $this->_rowOptions, $option ) : $this->_rowOptions[] = $option;
    }
	
    /**
     * returns the html of what to display if there are no records to display 
     *
     * @param void
     * @return string
     */
    protected function getNoRecordMessage()
    {	
		$html = '<div class="noRecord">';
		$html .= $this->_noRecordMessage;
		$html .= '</div>';
		return $html;
    }
	
    /**
     * Changes the default message if no record is found 
     *
     * @param void
     * @return string
     */
    public function setNoRecordMessage( $msg )
    {	
		$this->_noRecordMessage	= strlen( $msg ) > 7 ? $msg : $this->_noRecordMessage;
    }
	
    /**
     * Set primary key used for customized link 
     *
     * @param string
     * @return void
     */
    public function setKey( $key )
    {	
		$this->_key = $key;
    }
	
    /**
     * Returns primary key used for customized link 
     *
     * @param void
     * @return string
     */
    public function getKey()
    {	
		return $this->_key;
    }
	
    /**
     * Returns the rows of data 
     *
     * @param void
     * @return array
     */
    public function setRows()
    {	
		// Collect values for the current page
		$this->_rows = $this->getPages( $this->_currentPage );
		$this->_noOfPageRecords = count( $this->_rows ); 
    }
	
    /**
     * Returns the rows of data 
     *
     * @param void
     * @return array
     */
    public function getRows()
    {	
		if( null === $this->_rows )
		{
			$this->setRows();
		}
					//		var_export( $this->_rows );

		return (array) $this->_rows;
    }
	
    /**
     * returns an array of error msgs 
     *
     * @param void
     * @return array
     */
    public function getBadnews()
    {	
		return $this->_badnews;
    }
	
    /**
     * Returns XHTML showing the paginated data
     *
     * @param void
     * @return string
     */
    public function view()
    {	
		$list = $this->getList();
		$content = null;  
		$content .= $this->listTitle ? '<div><h3 class="pc-heading">' . $this->listTitle . '</h3></div>' : null;     
		$content .= '<div style="background-color:#d4d4d4;">';     		
		if( $this->_noOfPageRecords !== $this->_noOfRecords )     
		{	
//			$content .='<p style="font-size:smaller;">Showing ' . $this->_noOfPageRecords . ' out of ' . $this->_noOfRecords . ' Record(s). Show ' . $noToShow .' ' . $order .'</p>';
		}
		$creatorClass = explode( '_', $this->pageName );         
		$listClass = $creatorClass;
		$file = array_pop( $creatorClass );
		
	//	var_export( $file );
		if( $result = $this->getRows() )
		{
			if( @$this->showExportLink )
			{

				$downloadLink = Ayoola_Application::getUrlPrefix() . '/widgets/' . $this->pageName . '/?export_list=' . $this->pageName . '&' . http_build_query( $_GET );

				$this->setListOptions( array( 'Export' => '<a rel="" href="' . $downloadLink . '" title="" class="" style="" onClick="">Export List</a>' ) ); 
			}
			if( @$_GET['export_list'] == $this->pageName )
			{
				// filename for download
				$filename = ( $this->listTitle ? : $this->pageName ) . date('-Y-m-d') . ".csv";

				header("Content-Disposition: attachment; filename=\"$filename\"");
				header("Content-Type: text/csv");

				$out = fopen("php://output", 'w');

				$flag = false;
				//	var_export( $result );
				//	var_export( $this->fields );
				if( ! function_exists( 'cleanData' ) )
				{
					function cleanData(&$str)
					{
				//		if( ! is_scalar( $str ) ){ return false; }
					//	if($str == 't') $str = 'TRUE';
					//	if($str == 'f') $str = 'FALSE';
					//	if(preg_match( "/^0/", $str ) || preg_match( "/^\+?\d{8,}$/", $str ) || preg_match("/^\d{4}.\d{1,2}.\d{1,2}/", $str)) 
						{
					//		$str = "'$str ";
						}
						if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
					}
				}
				while( $row = array_shift( $result ) ) 
				{
					unset( $row['form_data'] );
					if( ! $flag ) 
					{
						// display field/column names as first row
					//	var_export( array_keys( $row ) );
						ksort( $row );
						$header = $row;
						fputcsv( $out, array_keys( $header ), ',', '"' );
					//	var_export( $this->fields );
					//	fputcsv( $out, array_keys( $this->fields ), ',', '"' ); 
						$flag = true;
					}
				//	foreach( $row )
					$notFoundKeys = array_diff_key( $header, $row );

					$notFoundKeys = array_combine( array_keys( $notFoundKeys ), array_fill( 0, count( $notFoundKeys ), ' ' ) );
				//	var_export( $notFoundKeys );
					$row += $notFoundKeys;
					$row = array_intersect_key( $row, $header );
					ksort( $row );
					array_walk( $row, __NAMESPACE__ . '\cleanData' );
					fputcsv( $out, array_values( $row ), ',', '"' );
				//	var_export( $row );
					//	fputcsv( $out, array_values( $row ), ',', '"' );
				}

				fclose($out);
				exit;
  			}
		}
		if( $file === 'List' )
		{
			array_push( $creatorClass, 'Creator' );
			$creatorClass = implode( '_', $creatorClass );
			if( ! isset( $this->_listOptions['Creator'] ) )  
			{
				$this->setListOptions( array( 'Creator' => '<a rel="" href="javascript:;" title="Add new to the list" class="" style="" onClick="ayoola.spotLight.showLinkInIFrame( \'' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/object_name/' . $creatorClass . '/\', \'' . $this->pageName . '\' )">Add new</a>' ) ); 
			}
		}
		$noToShow = null;
		$order = null;
		$content .= ' <div style="" class="pc-btn-parent">';			
		if( $this->_noOfRecords > 10 ) 
		{
		
			$noToShow = '<select class="pc-element-like-btn" onChange="window.location.search = window.location.search + \'&noPerPage=\' + this.value;">
						<option>' . $this->_noOfPageRecords . ' out of ' . $this->_noOfRecords . '</option>  O
						<option>10</option>
						<option>20</option>
						<option>50</option>
						<option>100</option>
						<option>200</option>
						<option>300</option>
						<option>500</option>
					</select>
					';
			$order = '<select class="pc-element-like-btn" onChange="window.location.search = window.location.search + \'&pc_sort_order_inverse=\' + this.value;">
						<option>Order...</option>  
						<option value=' . ( @$_GET['pc_sort_order_inverse'] ? '0' : '1' ) . '>Inverse</option>
					</select>
					';
			$content .='' . $noToShow .' ' . $order .'';
		}  
		if( $this->_listOptions )
		{
		//	$content .= '<h4>OPTIONS:</h4>';
			foreach( $this->getListOptions() as $option )
			{
				if( ! trim( $option ) )
				{
					continue;
                }
                $option = self::__( $option );

				$content .= '' . $option . '';			
			}
		}
		$content .= '</div> ';					
		if( $this->_noOfPageRecords !== $this->_noOfRecords )     
		{	
			if( $this->showPagination  ){ $content .= $this->getPagination(); }
		}
		if( $this->showSearchBox  )
		{ 
			$keys = @array_keys( array_pop( $this->getData() ) );
			if( $keys  )
			{ 
				//	Put search 
				$keys = array_combine( $keys, $keys );
				$newForm = new Ayoola_Form( array( 'name' => 'xxx', 'class' => 'xxx', 'data-not-playable' => true, 'method' => 'GET', 'action' => '?' . http_build_query( $_GET ), ) );
				$newForm->setParameter( array( 'no_fieldset' => true, 'no_required_fieldset' => true, ) );
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->hashElementName = false;
				$newFieldSet->addElement( array( 'name' => 'db_where_clause_field_value',  'label' => '',  'multiple' => 'multiple', 'placeholder' => 'Search ' . $this->listTitle . '...', 'style' => 'width:100%; max-width:500px;text-transform:none;cursor:auto', 'class' => 'pc-element-like-btn', 'type' => 'InputText', 'value' => null ) );
				$newFieldSet->addElement( array( 'name' => 'db_where_clause_field_name', 'onchange' => 'this.form.submit()', 'class' => 'pc-element-like-btn', 'style' => 'width:100%; max-width:300px;cursor:auto',  'label' => '  ',  'multiple' => 'multiple', 'type' => 'Select', 'value' => null ), array( 'Select Search Column...' ) + $keys );
				$newForm->addFieldset( $newFieldSet );  
				
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->hashElementName = false;
				$newFieldSet->addElement( array( 'name' => 'search-object', 'type' => 'Hidden', 'value' => $this->pageName ) );
				$newForm->addFieldset( $newFieldSet );     
				$content .= $newForm->view();  
			}
		}
		$content .= '</div>';     		
		$content .= $list;
		return $content;
    }
	
	// END OF CLASS
}
