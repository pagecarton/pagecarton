<?php

/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
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
 * @category   PageCarton CMS
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
	
	public $hideCheckbox = true;
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
	//	if( Ayoola_Application::getUserInfo( 'access_level' ) == 99 )
		{
	//		var_export( count( $files ) );
	//		var_export( '<br />' );
		//	var_export( $data );
	//		var_export( '<br />' );
		}
 	//	var_export( count( $data ) );
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
/*         $first = is_null( $this->_firstPage ) ? NULL : $this->_rep['first'];
		$last = is_null( $this->_lastPage ) ? NULL : $this->_rep['last'];
		$next = is_null( $this->_nextPage ) ? NULL : $this->_rep['next'];
		$previous = is_null( $this->_previousPage ) ? NULL : $this->_rep['previous'];
 */     $first = $this->_rep['first'];
		$last = $this->_rep['last'];
		$next = $this->_rep['next'];
		$previous = $this->_rep['previous'];
		$classPlayer = '' . Ayoola_Application::getUrlPrefix() .'/tools/classplayer/get/object_name/' . $this->pageName . '/';
		$html = null;
		$options = range( 100, 500, 100 );
		$options = array_combine( $options, $options );
	//	$html = str_ireplace( '{{{---@@@BADNEWS@@@---}}}', '', $html );
	//	$html .='<p>Showing ' . $this->_noOfPageRecords . ' out of ' . $this->_noOfRecords . ' Record(s). Show ' . $noToShow .'</p>';
		require_once 'Ayoola/Page.php';
		$html .='<table class="pc-table">
					<tr>';
		$html .= is_null( $this->_firstPage ) ? NULL : ( '<td>
							<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_firstPage . '/;changeElementId=' . $this->pageName . '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_firstPage ) ) . '">' . $first. '
							</a>
						</td>' );
		$html .= is_null( $this->_previousPage ) ? NULL : ( '<td>
							<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_previousPage . '/;changeElementId=' . $this->pageName . '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_previousPage ) ) . '">' . $previous. '
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
					$html .= 		'<span onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews selectednews"> ' . $label . ' 
								</span>  ';
				}
				else
				{
		//			$html .= ' 		<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" rel="classPlayerUrl=' . $classPlayer . 'page/' . $i . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $i ) ) . '">' . $label . '</a> ';
					$html .= ' 		<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" rel="" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $i ) ) . '">' . $label . '</a> ';
				}
			}
			
			$html .= 		'</td>';
		}
		$html .= is_null( $this->_nextPage ) ? NULL :		'<td style="text-align:right;" >
							<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" style="text-align:right;" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_nextPage . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_nextPage ) ) . '">' . $next. '</a></td>';
		$html .= is_null( $this->_lastPage ) ? NULL :		'<td style="text-align:right;" >
							<a onClick="ayoola.div.selectElement( { element: this, disableUnSelect: true, name: \'paginator_navigation\' } );" name="paginator_navigation" class="boxednews normalnews" style="text-align:right;" rel="classPlayerUrl=' . $classPlayer . 'page/' . $this->_lastPage . '/;changeElementId=' . $this->pageName. '" href="' . Ayoola_Page::appendQueryStrings( array( 'page' => $this->_lastPage ) ) . '">' . $last. '</a>
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
			setcookie( 'noPerPage', $_GET['noPerPage'] ); // Sets the Number of records per page as a cookie
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
		$this->_noPerPage = ( $this->_noPerPage > 0 && $this->_noPerPage < 501 ) ? $this->_noPerPage : 10;
		
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
		if( ! $this->getRows() ){ return $this->_list = $this->getNoRecordMessage(); }
		$key = $this->getKey() ? : null;
	//	var_export( $key );
		$bg = '#eeeeee';
		$html = '<table  class="pc-table"><tr bgcolor="' . $bg . '">';
		$html .= @$this->hideCheckbox ? null : '<th><input type="checkbox" name=' . $key . ' value="0" /></th>';
		$html .= @$this->hideNumbering ? null : '<th>ID</th>';
		foreach( $fields as $field => $value )
		{
			require_once 'Ayoola/Filter/UnderscoreToSpace.php';
			$filter = new Ayoola_Filter_UnderscoreToSpace;
			$head = $filter->filter( $field );
			$html .='<th>' . $head . '</th>';
		}
		$html .='</tr>';
		$allRows = $this->getRows();
		foreach( $allRows as $counter => $row )
		{
			$row = is_scalar( $row ) ? array( $this->_key => $row ) : $row;
			$row = $this->rowDataColumn ? $row[$this->rowDataColumn] : $row;
			
			$bg = $bg == '#ffffff' ? '#eeeeee' : '#ffffff';
			$records = '<tr style="background-color:' . $bg . '; color:#000;">';    
			$records .= @$this->hideCheckbox ? null : '<td><input type="checkbox" name="' . $key . '" value="' . $row[$key] . '" /></td>';
			$records .= @$this->hideNumbering ? null : '<td>'. ++$counter . '</td>';
//		var_export( $row );
			foreach( $fields as $field => $value )
			{
			//	if( is_array( $row ) && array_key_exists( $field, $row ) )
				if( array_key_exists( $field, $row ) )
				{
					
					// make adequate  replacement if required 
					$value =  $value ? : $row[$field];
					
				//	if( is_array( $value ) )
					{ 
				//		var_export( $value );
					//	$value = print_r( $value, true ); 
					}
//	var_export( $value );
					if( is_array( $value ) && ( ! empty( $value['value'] ) || ! empty( $value['filter'] ) ) )
					{
						$value['value'] = @$value['value'] ? : $row[$field];
				//		if( ! empty( $value['filter'] ) && $value['filter'] implements Ayoola_Filter_Interface )
						if( ! empty( $value['filter'] ) )
						{
							$value['filter'] = new $value['filter'];
							$value = $value['filter']->filter( $value['value'] );
						}
					}
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
					
				//	$value = str_replace( '%FIELD%', is_scalar( $row[$field] ) ? $row[$field] : null, $value );
					$value = str_replace( '%FIELD%', is_scalar( $row[$field] ) ? $row[$field] : null, is_scalar( $value ) ? $value : null );
					$value = str_replace( '%KEY%', $row[$key], $value );
				//	$value = htmlentities( $value );
					$records .='<td>' . $value . '</td>';
				}
				else
				{
					// I made this to allow for links like delete, edit, etc
					$value = str_replace( '%KEY%', $row[$key], $value );
					$records .='<td> ' . $value . '</td>';
				}
				
			}
			$records .= '</tr>';
			$html .= $records;
		}
		$this->_rows = array(); // Free up the memory
		$html .='</table>';		
		
		// Embed the html in a form so as to service the checkboxes
		//	I am going to be using Ayoola_Form 
		//	I will need to create a method to implement adding html to Ayoola_Form ummmmmmmmm
		// wow, I only needed to add a new method addHtml to Ayoola_Form_Element. THANK GOD!!
		
		require_once 'Ayoola/Form.php';
		require_once 'Ayoola/Form/Element.php';
		$form = new Ayoola_Form( 'method=>' . $this->formMethod . ' :: name=>' . $this->pageName );
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
		$content = null;
		$content .= $this->listTitle ? '<h2>' . $this->listTitle . '</h2>' : null;
		
		
		
		$noToShow = '<select onChange="window.location.search = window.location.search + \'&noPerPage=\' + this.value;">
					<option>9</option>
					<option>10</option>
					<option>20</option>
					<option>50</option>
					<option>100</option>
					<option>200</option>
					<option>300</option>
					<option>500</option>
				</select>
				';
		$content .='<p>Showing ' . $this->_noOfPageRecords . ' out of ' . $this->_noOfRecords . ' Record(s). Show ' . $noToShow .'</p>';
		$creatorClass = explode( '_', $this->pageName );
		$listClass = $creatorClass;
		$file = array_pop( $creatorClass );
		
	//	var_export( $file );
		if( $file === 'List' )
		{
			array_push( $creatorClass, 'Creator' );
			$creatorClass = implode( '_', $creatorClass );
			if( ! isset( $this->_listOptions['Creator'] ) )  
			{
				$this->setListOptions( array( 'Creator' => '<a rel="" href="javascript:;" title="Add new to the list" class="" style="" onClick="ayoola.spotLight.parameters.changeElementId=\'' . $this->pageName . '\';ayoola.spotLight.showLinkInIFrame( \'' . @constant( 'PC_PATH_PREFIX' ) .'/tools/classplayer/get/object_name/' . $creatorClass . '/\')">Add new</a>' ) ); 
			}
		}
		if( $this->_listOptions )
		{
		//	$content .= '<h4>OPTIONS:</h4>';
			foreach( $this->getListOptions() as $option )
			{
			//	$content .= ' <span class="boxednews centerednews selectednews"> ' . $option . ' </span> ';			
		//		$content .= ' <button type="button"> ' . $option . ' </button> ';			
				$content .= ' <span class="normalnews boxednews "> ' . $option . ' </span> ';			
			}
		}
		if( $this->showPagination  ){ $content .= $this->getPagination(); }
		if( $this->showSearchBox  )
		{ 
			$keys = @array_keys( array_pop( $this->getData() ) );
			if( $keys  )
			{ 
				//	Put search 
				$keys = array_combine( $keys, $keys );
				$newForm = new Ayoola_Form( array( 'name' => 'xxx', 'data-not-playable' => true, 'method' => 'GET', 'action' => '?' . http_build_query( $_GET ), ) );
				$newForm->setParameter( array( 'no_fieldset' => true, 'no_required_fieldset' => true, ) );
				$newForm->submitValue = 'Search';
			//	$newForm->setParameter( array( 'no_form_element' => true ) );
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->allowDuplication = true;
				$newFieldSet->hashElementName = false;
				$newFieldSet->addLegend( 'Search records...' );
				$newFieldSet->addElement( array( 'name' => 'db_where_clause_field_name',  'label' => 'Field Name',  'multiple' => 'multiple', 'type' => 'Select', 'value' => null ), $keys );
				$newFieldSet->addElement( array( 'name' => 'db_where_clause_field_value',  'label' => 'Field Value',  'multiple' => 'multiple', 'placeholder' => 'e.g. John', 'type' => 'InputText', 'value' => null ) );
				$newForm->addFieldset( $newFieldSet );  
				
				$newFieldSet = new Ayoola_Form_Element;
				$newFieldSet->container = 'span';
				$newFieldSet->hashElementName = false;
				$newFieldSet->addElement( array( 'name' => 'search', 'type' => 'Submit', 'value' => 'Search' ) );
				$newFieldSet->addElement( array( 'name' => 'search-object', 'type' => 'Hidden', 'value' => $this->pageName ) );
				$newForm->addFieldset( $newFieldSet );     
				$content .= $newForm->view();  
			}
		}
		$content .= $this->getList();
	//	if( ! @$this->hideOptions  )
		{ 
		//	$fieldset = new Ayoola_Form_Element();
			
		}
	//	$content .= $this->getList();
		return $content;
    }
	
	// END OF CLASS
}
