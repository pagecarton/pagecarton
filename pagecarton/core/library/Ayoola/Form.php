<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @user   Ayoola
 * @package    Ayoola_Form
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Form.php 4.17.2012 7.55am ayoola $
 */

/**
 * @see Ayoola_Exception 
 */
 
require_once 'Ayoola/Exception.php';


/**
 * @user   Ayoola
 * @package    Ayoola_Form
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Form extends Ayoola_Abstract_Playable
{
	
    /**
     * The default attributes of the form
     * 
     * @var array
     */
	protected $_defaultAttributes = array(
											'name' => 'form', 
											'method' => 'post', 
											'enctype' => 'application/x-www-form-urlencoded', 
										);
    /**
     * Call to Action for this form
     * 
     * @var string 
     */				
	public $callToAction;
	
    /**
     * Inject the next or clear command to form.
     * 
     * @var array 
     */				
	public $actions = array();
	
    /**
     * Description of the Form
     * 
     * @var string 
     */				
	protected $_description;
	
    /**
     * 
     * @var array 
     */				
	public $requiredElements = array();
	
    /**
     * Validators of the Form
     * 
     * @var array 
     */				
	protected $_requirements = array();
	
    /**
     * External form requirements
     * 
     * @var array 
     */				
	protected $_formRequirements = array();

    /**
     * Attributes of the Form
     * 
     * @var array 
     */				
	protected $_attributes = array();
	
    /**
     * Holds the fieldsets
     * 
     * @var array 
     */				
	protected $_fieldsets = array();
	
    /**
     * Filters of the Form
     * 
     * @var array 
     */				
	protected $_filters = array();
	
    /**
     * An array of error messages
     * 
     * @var boolean 
     */					
	protected $_badnews = array();
	
    /**
     * Names of the fields on the form
     * 
     * @var array 
     */							
	protected $_names = array();
	
    /**
     * Name/Value pairs of the fields on the form
     * 
     * @var array 
     */							
	protected $_values = array();
	
    /**
     * Imported Values from GET or POST superglobal
     * 
     * @var array 
     */							
	protected $_global = array();
	
    /**
     * Values to use as default values in elements
     * 
     * @var array 
     */							
	protected static $_defaultValues = array();
	
    /**
     * Values can be injected through this property
     * 
     * @var array
     */
	public $fakeValues = array();
	
    /**
     * 
     * 
     * @var string 
     */				
	public $formNamespace;
	
    /**
     * Value for submit button
     * 
     * @var string 
     */				
	public $submitValue;
	
    /**
     * Flag to whether to display error message before elements
     * 
     * @var boolean 
     */				
	public $badnewsBeforeElements = true;
	
    /**
     * Flag to whether to display error message before each elements
     * 
     * @var boolean 
     */				
	public $badnewsPerElement = true;
	
    /**
     * Switch to false to disable error display
     * 
     * @var boolean 
     */				
	public $showBadnews = true;
	
    /**
     * Wrap form with a div element.
     * 
     * @var boolean 
     */				
	public $wrapForm = true;
	
    /**
     * Switch to true to load each fieldset at a time
     * 
     * @var boolean 
     */					
	public $oneFieldSetAtATime = false;
	
    /**
     * Switch to true to put captcha in the form
     * 
     * @var boolean 
     */							
	public $useCaptcha = false;
	
    /**
     * Stop validation on the first error
     * 
     * @var boolean 
     */						
	protected $_breakOnFailure = false;
	
    /**
     * Switch to true to indicate that form has been validated
     * 
     * @var boolean 
     */							
	protected $_validated = false;
	
    /**
     * Switch to true to indicate that completion of fieldsetAtAtime
     * 
     * @var boolean 
     */							
	protected $_stageCompleted = false;
	
    /**
     * Mark-up to display the form
     * 
     * @var string 
     */							
	protected $_form; 
	
    /**
     * whether to check and filter values
     * 
     * @var boolean 
     */							
	protected static $_checkValues = true; 
	
    /**
     * whether to check and filter values
     * 
     * @var boolean 
     */							
	protected static $_placeholders =  array( 'badnews' => '{{{---@@@BADNEWS@@@---}}}' ); 
	
    /**
     * Appended String for Submission detector Element
     * 
     * @var string 
     */					
	const SUBMIT_DETECTOR = 'SUBMIT_DETECTOR';
	
    /**
     * 
     * 
     * @var string 
     */					
	const REFRESH_INDICATOR = 'REFRESH_INDICATOR';
	
    /**
     * 
     * 
     * @var string 
     */					
	const BACKBUTTON_INDICATOR = 'BACKBUTTON_INDICATOR';
	
    /**
     * 
     * 
     * @var string 
     */					
	const HONEY_POT = 'Delicious';
	
    /**
     * Initialize a new form
     *
     * @param array	The attributes of the form
     * @return void
     */
    public function __construct( $attributes = null )
    {
		$this->_attributes = _Array( $attributes );
		$this->_attributes['id'] = @$this->_attributes['id'] ? : $this->_attributes['name'] . '_form_id';
		
		//	testing 123
	//	$this->_attributes['action'] = '/tools/classplayer/get/object_name/' . $this->_attributes['name'] . '/';
		$this->_attributes['class'] = @$this->_attributes['class'] ? : 'pc-form2';
//	var_export( $this->_attributes['class'] );
		$this->_attributes['action'] = @$this->_attributes['action'] ? : '#' . $this->_attributes['name'];
		
/* 		//	Check if form was submitted
		if( @$_REQUEST[$this->_attributes['name'] . self::SUBMIT_DETECTOR] === $this->_attributes['name'] )
		{
			$this->_submitted = true;
		}
 */	//	echo $this->getForm();
	//	var_export( $this->_attributes );
	//	$this->setViewContent( $this->getForm() );
    }
	
    /**
     * Filters the values
     *
     * @param void
     * @return boolean
     */
    protected function _filter( $name )
    {	
	//	var_export( $this->_filters[$name] );
		if( ! @is_array( $this->_filters[$name] ) ){ return false; }
		
		//	Filters values before validation
		$filter = 'Ayoola_Filter_';
		foreach( $this->_filters[$name] as $seive => $parameter )
		{
			$requiredFilter = $filter . ucfirst( $seive );
			if( ! Ayoola_Loader::loadClass( $requiredFilter ) )
			{ 
				continue;
			//	throw new Ayoola_Exception( 'INVALID FORM FILTER: ' . $requiredFilter  );
			}
			$process = new $requiredFilter;

			//convert parameter to array and then autofill prior to filtering
			$parameter = is_array( $parameter ) ? $parameter : array_map( 'trim', explode( ';', $parameter ) );				
			$process->validationParameters = $parameter;
			if( method_exists( $process, 'autofill' ) ) 
			{
				$process->autofill( $parameter );  
			}
			
			//	The real filter process
			if( is_array( $this->_values[$this->_names[$name]['real_name']] ) && @$process->loopFilter !== false )   
			{
				foreach( $this->_values[$this->_names[$name]['real_name']] as $key => $each )
				{ 
					$this->_values[$this->_names[$name]['real_name']][$key] = $process->filter( $this->_values[$this->_names[$name]['real_name']][$key] );
				}
			}
			else
			{ 
	//			var_export( strlen( $this->_values[$this->_names[$name]['real_name']] ) );
	//			var_export( '<br />' );
		//		var_export( $parameter );
				$this->_values[$this->_names[$name]['real_name']] = $process->filter( $this->_values[$this->_names[$name]['real_name']] );
			//	var_export( $process->filter( $this->_values[$this->_names[$name]['real_name']] ) );
			}
		}
		
    }
	
    /**
     * Validate the values
     *
     * @param void
     * @return boolean
     */	
    protected function _validate( $name )
    {
/* 		//	Extra check for non-admin
		if( ! Ayoola_Form::hasPriviledge() )
		{
			//	Turning this to array allows to validate array values
			$values = $this->_values[$this->_names[$name]['real_name']];
			$values = is_array( $values ) ? $values : array( $values );
		//		var_export( $this->_values );
			foreach( $values as $each )
			{
				if( $each != strip_tags( $each, '<span> <font> <div> <a> <address> <em> <strong> <u> <b> <i> <big> <small> <sub> <sup> <cite> <code> <img> <ul> <ol> <li> <dl> <lh> <dt> <dd> <br> <p> <table> <th> <td> <tr> <pre> <blockquote> <nowiki> <h1> <h2> <h3> <h4> <h5> <h6> <hr> <kbd> <s> <strike> <del>' ) )  
				{
								
					//	Notify Admin
					$mailInfo['subject'] = 'Alert! Code Injection by user';
					$mailInfo['body'] = 'Alert! Code Injection by user
					Info: ' . var_export( $this->_values, true ) . '.
					';
				//	Application_Log_View_General::log( array( 'type' => 'New Post', 'info' => array( $mailInfo ) ) );
					try
					{
						@Ayoola_Application_Notification::mail( $mailInfo );
					}
					catch( Ayoola_Exception $e ){ null; }
					$this->setBadnews( 'Not allowed! Why are you trying to put code into PageCarton?', $name );
					return false;
				} 
			}
		}
 */		
		if( ! @is_array( $this->_requirements[$name] ) ){ return true; }
		$validator = 'Ayoola_Validator_';
		foreach( $this->_requirements[$name] as $requirement => $parameter )
		{ 
			$requiredValidator = $validator . ucfirst( $requirement );
			if( ! Ayoola_Loader::loadClass( $requiredValidator ) )
			{ 
				//	invalid validator means its always invalid
				return false;
			//	throw new Ayoola_Exception( 'INVALID FORM VALIDATOR: ' . $requiredValidator  );
			}
			$check = new $requiredValidator;
			$parameter = is_array( $parameter ) ? $parameter : array_map( 'trim', explode( ';;', $parameter ) );
			$check->validationParameters = $parameter;

			//convert parameter to array and then autofill
			if( method_exists( $check, 'autofill' ) )
			{
										
				$check->autofill( $parameter );
			}
			
			//	Turning this to array allows to validate array values
			$values = $this->_values[$this->_names[$name]['real_name']];
			$values = is_array( $values ) ? $values : array( $values );
		//		var_export( $this->_values );
			foreach( $values as $each )
			{
	//			var_export( $check->validate( $each ) );
				if( ! $check->validate( $each ) )
				{	
					$label = $this->_names[$name]['label'] ? : 'This field';
					$badnews = str_ireplace( array( '%value%', '%variable%' ), array( '"' . $label . '"', ( is_scalar( $each ) ? $each : null ) ), isset( $parameter['badnews'] ) ? $parameter['badnews'] : $check->getBadNews() );
					
					//	Allow optional elements
				//	if( $this->_names[$name]['real_name'] === 'bbm_pin' )
					{
				//			self::v( $each );			
				//			self::v( $this->_names[$name]['optional'] );			
					}
					if( isset( $this->_names[$name]['optional'] ) )
					{
					//	var_export( $each );
						if( $each === '' )
						{
							continue; 
						}
					}
					else
					{
						$this->setBadnews( $badnews, $this->_names[$name]['real_name'] );
						
						//	Break on first failure
						return false; 
					}
					
				}
			}
		}
    }
	
    /**
     * Return Fieldsets containing universal elements
     *
     * @param void
     * @return Ayoola_Form_Element
     */	
    public function getRequiredFieldset()
    {
        $element = new Ayoola_Form_Element;
		$element->useDivTagForElement = false;
		if( $this->useCaptcha )
		{
			$element->addElement( 'name=>captcha:: type=>Captcha' );
			$element->addRequirement( 'captcha','Captcha' );
		}
		if( $this->submitValue && ! $this->callToAction )
		{
		//	var_export( $this->submitValue );
			$element->addElement( array( 'name' => 'submit-' . $this->submitValue . '', 'value' => $this->submitValue . '   » ', 'type' => 'Submit', 'style' => 'display:block; margin: 0.5em 0 0.5em 0 ;', 'class' => '', 'data-pc-ignore-field' => 'true' ) );    
		}
		foreach( $this->requiredElements as $key => $value )
		{
		//	var_export( $this->submitValue );
			$element->addElement( array( 'name' => $key, 'value' => $value, 'type' => 'Hidden' ) );
		}
		$element->addElement( array( 'name' => $this->_attributes['name'] . self::SUBMIT_DETECTOR, 'value' => $this->_attributes['name'], 'type' => 'Hidden', 'data-pc-ignore-field' => 'true' ) );
	//	$element->addElement( array( 'name' => 'MAX_FILE_SIZE', 'type' => 'Hidden', 'value' => '107374182', 'data-pc-ignore-field' => 'true' ) );
	//	$element->addElement( array( 'name' => self::HONEY_POT, 'type' => 'HoneyPot', 'data-pc-ignore-field' => 'true' ) );
		$element->addFilters( 'Trim:: Escape::Alnum' );
		return $element;
	}
	
    /**
     * Returns _formRequirements
     *
     * @param void
     * @return array
     */		
    public function getFormRequirements()
    {
		return $this->_formRequirements;
	}
	 
    /**
     * Adds form requirements
     *
     * @param mixed
     * @return array
     */		
    public function setFormRequirements( $requirements )
    {
//		var_export( $requirements );
	
		//	ADD FORM REQUIREMENTS
		$requirements = is_string( $requirements ) ? array_map( 'trim', explode( ',', $requirements ) ) : $requirements;
	//	$requirements = is_array( $requirements ) ? array_unique( $requirements ) : array();
		$requirements = is_array( $requirements ) ? $requirements : array();
		if( ! $requirements ){ return $requirements; }
				
		//	Test the first element to detect if each content is an array of ready content
		$a = $requirements;
		@$a = array_shift( $a );
		$table = new Ayoola_Form_Requirement();
/* 		if( $requirements && ! is_array( $a ) )  
		{
			$requirements = $table->select( null, array( 'requirement_name' => $requirements ) );
		}
 *///		
	//	var_export( $requirements );
	//	$getFieldset = '';
	//	$getFieldset = create_function( '$class', $getFieldset );
		switch( gettype( $a ) )
		{
			case 'string':
				$requirements = $table->select( null, array( 'requirement_name' => $requirements ) );
			break;
		}
	//	$requirements = array();
	//	var_export( $requirements );
		if( $requirements )
		{
		//	$this->submitValue = $this->submitValue ? : 'Continue...';
		}
		foreach( $requirements as $each )  
		{
		//	continue;
		 //	var_export( $each );
			switch( gettype( $each ) )
			{
				case 'object':
					$class = $each;
					if( ! method_exists( $class, 'createForm' ) ){ continue; }
					$this->_formRequirements[] = $class;
					$fieldsets = $class->getForm()->getFieldsets();
					foreach( $fieldsets as $fieldset )
					{
						$fieldset->appendElement = false;
						$this->addFieldset( $fieldset );
					}
				break;
				case 'array':
					$combinedInfo = $each;
				//	self::v( @$combinedInfo['parameters'] );
					if( ! empty( $each['requirement'] ) )
					{
						$b = $table->selectOne( null, array( 'requirement_name' => $each['requirement'] ) );
						$combinedInfo = array_merge( $b, $each );
					}
			//		var_export( $combinedInfo );
					$class = @$combinedInfo['requirement_class'];	 
				//	continue;
				//	var_export( $class );
					
					if( ! Ayoola_Loader::loadClass( $class ) )
					{
						continue;
				//		throw new Ayoola_Object_Exception( 'INVALID CLASS: ' . $class );
					}
			//		$class = new $class( @self::$_requirementOptions[$combinedInfo]['parameters'] );
				//	var_export( $combinedInfo['parameters'] );
					$class = new $class( @$combinedInfo['parameters'] );
				//	$class = new $class();
				//	continue;
				//	var_export( $combinedInfo );
					if( ! method_exists( $class, 'createForm' ) ){ continue; }
					$this->_formRequirements[] = $class;
					$fieldsets = $class->getForm()->getFieldsets();
					$this->actions += $class->getForm()->actions;
					foreach( $fieldsets as $key => $fieldset )
					{
						$fieldset->appendElement = false;
						@$fieldset->addLegend( $this->getParameter( $each['requirement_name'] . '_fieldset_legend' ) ? : ( $combinedInfo['requirement_legend'] . ( $fieldset->getLegend() ?  ' | ' . $fieldset->getLegend() : null  ) ) );
						if( @$b['requirement_goodnews'] )
						{
							$fieldset->addElement( array( 'type' => 'html', 'name' => $key . '_Xe' ), array( 'html' => '<blockquote class=""><em class="">' . str_ireplace( '@@@requirement_title@@@', @$combinedInfo['requirement_title'] , $b['requirement_goodnews'] ) . '</em></blockquote>' ) );
						}
						if( @$each['requirement_goodnews'] )
						{
							$fieldset->addElement( array( 'type' => 'html', 'name' => $key . '_e' ), array( 'html' => '<blockquote class=""><em class="">' . str_ireplace( '@@@requirement_title@@@', @$combinedInfo['requirement_title'] , $each['requirement_goodnews'] ) . '</em></blockquote>' ) );
						}
						$this->addFieldset( $fieldset );
					}
				break;
			}
		}
		return $requirements;
    }
	
    /**
     * Return markup for form
     *
     * @param void
     * @return string
     */		
    public function getForm( array $formOptions = null )
    {
	//	var_export( $this->_form );
		if( null === $this->_form ){ $this->setForm( $formOptions ); }	
        return $this->_form;
    }
	
    /**
     * Creates the form
     *
     */		
    public function setForm( array $formOptions = null )
    {
	//	var_export( __LINE__ );
	//	array_push( $this->_fieldsets, $this->getRequiredFieldset() );
	//	var_export( $formOptions );
	//	if( is_array( $_POST['fake_values_namespace'][$this->formNamespace] ) )
		if( is_array( @$_POST['Ayoola_Form'][$this->formNamespace]['fake_values'] ) )
		{
			$this->fakeValues = $_POST['Ayoola_Form'][$this->formNamespace]['fake_values'];
			//	var_export( $this->fakeValues );
		}
		if( $this->getOneFieldSetAtATime() && isset( $_POST['Ayoola_Form'][$this->formNamespace]['one_field_set_at_a_time'] ) )
		{
			$this->oneFieldSetAtATime = $_POST['Ayoola_Form'][$this->formNamespace]['one_field_set_at_a_time'];
	//		var_export( $this->fakeValues );
		}
		
		$this->_attributes = $this->_attributes ? array_merge( $this->_defaultAttributes, $this->_attributes ) : $this->_defaultAttributes;
		$this->_attributes['id'] .= $this->formNamespace;
	//	var_export( $this->_attributes );
		
		$form = null;
		$form .= $this->wrapForm ? "<div id='{$this->_attributes['id']}'>\n" : null;
		if( ! $this->getParameter( 'no_form_element' ) )
		{
			$form .= "<form data-pc-form=1 ";   
			foreach( $this->_attributes as $key => $value )
			{
				$form .= empty( $this->_attributes[$key] ) ? null : " $key='$value' ";
			}
			
			$this->getGlobal();	//	Load the global
			$form .= ">\n";
		}
	//	if( ! empty( $_GET['pc_inspect_widget_form'] ) )
		{
	//		$form .= var_export( $this->_names, true );   
		}
		$i = 0;
		if( $this->getOneFieldSetAtATime() )
		{
			$values = self::getStorage( $this->_attributes['id'] )->retrieve();
			
			//	So we can also have this as global values like get and post
		//	self::getStorage( 'global_values' )->store( $values );
			$values = is_array( $values ) ? $values : array();
			
			//	So we can also have this as global values like get and post
		//	self::getStorage( 'global_values' )->store( $values );
	//		var_export( $_REQUEST[self::hashElementName( self::REFRESH_INDICATOR ) . "_x"] );
	//		var_export( $_REQUEST );
		//	var_export( $this->actions );
		//	var_export( in_array( self::BACKBUTTON_INDICATOR, $this->actions ) );
			
			if( isset( $_REQUEST[self::REFRESH_INDICATOR] ) || in_array( self::REFRESH_INDICATOR, $this->actions ) )
			{
		//	var_export( $values );
			//	unset( $values['oneFieldSetAtATime'] );
				self::getStorage( $this->_attributes['id'] )->clear();
				$_REQUEST[self::REFRESH_INDICATOR] = true;
		//		self::getStorage( $this->_attributes['id'] )->store( array() );
			//	self::getStorage( 'global_values' )->clear();
				
			}
			elseif( isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) || in_array( self::BACKBUTTON_INDICATOR, $this->actions ) )
			{
				$_REQUEST[self::BACKBUTTON_INDICATOR] = true;
				@array_pop( $values['oneFieldSetAtATime'] );
			//	$_GET = $values;
			//	var_export( $values );
				self::getStorage( $this->_attributes['id'] )->store( $values );
		//		self::getStorage( 'global_values' )->clear();
			}
		}
	//	$fieldsets = 
	
		//	ADD POST-FORM REQUIREMENTS
		$this->setFormRequirements( $this->getParameter( 'requirements' ) );
		
		
		if( @$values )
		{
			//	form filling in progress, no need for call to action
			$this->callToAction = false;
		}
		if( $this->callToAction )
		{
		//	$this->getOneFieldSetAtATime() = true;
		
			//	Show call to action, disable all elements
			$form .= $this->callToAction;
			$form .= '<span style="display:none;">';
		//	$this->_fieldsets = array();
		}
		
		//	Fieldset groups
		$group = null;
		foreach( $this->_fieldsets as $fieldsetId => $fieldset )
		{	
				//	var_export( $fieldsetId );
			if( $this->getOneFieldSetAtATime() )
			{
				$values = self::getStorage( $this->_attributes['id'] )->retrieve();
			//	var_export( $values );
				$values = is_array( $values ) ? $values : array();
				
				//	self::v( $values );
				if( @$values['oneFieldSetAtATime'][$fieldsetId] )
				{
					//	Stage already completed
				//	var_export( $fieldsetId );
				//	var_export( $values[$fieldsetId] );
				//	$trueValues = $values[$fieldsetId] +
				//	self::v( $values['oneFieldSetAtATime'][$fieldsetId] );
				//	self::v( $fieldsetId );
					continue;
				}
/* 				elseif( $i === 1 )
				{
				//	self::v( $group );
				//	self::v( $fieldsetId );
				//	self::v( $fieldset->group );
				//	if( $group == $fieldset->group )
					{
						//	Same Group, do it together
					}
				//	else
					{
						continue;
					}
				}
 */				elseif( $i )
				{
				//	self::v( $group );
				//	self::v( $fieldsetId );
				//	self::v( $fieldset->group );
				//	if( $group == $fieldset->group )
					{
						//	Same Group, do it together
					}
				//	else
					{
						continue;
					}
				}
/* 				else( $fieldset->group )
				{
					if( $group == $fieldset->group )
					{
						//	Same Group, do it together
					}
				}
 */			//	$group = $fieldset->group;
			}
			
			$each = $this->getElementMarkup( $fieldset );
		//	var_export( self::getStorage( 'global_values' )->retrieve() );
			if( $this->isSubmitted() && ! $this->_badnews && ! $this->_stageCompleted )
			{
				if( $this->getOneFieldSetAtATime() && ! isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) && ! isset( $_REQUEST[self::REFRESH_INDICATOR] ) )
				{
					//	var_export( $fieldsetId );
					//	var_export( $i );
					$values['oneFieldSetAtATime'][$fieldsetId] = true;
					$values = array_merge( $values, $this->_values );
					self::getStorage( $this->_attributes['id'] )->store( $values );
					
					//	So we can also have this as global values like get and post
			//		self::getStorage( 'global_values' )->store( $values );
					//	RE-IMPLEMENTING SESSION GLOBAL VALUES TO ACT AS PRESET VALUES IN OTHER FIELDS
					//	So we can also have this as global values like get and post
					$globalValues = self::getStorage( 'global_values' )->retrieve() ? : array();
				//	var_export( $globalValues );
				//	var_export( $this->_values );
				//	$globalValues = array_merge( $globalValues, $this->_values );
					$globalValues = $this->_values + $globalValues;
					if( $this->_values )
					{
					//	var_export( $this->_values );
					//	var_export( $globalValues );
						list( $globalValues ) = array_chunk( $globalValues, 100, true );
					//	var_export( $globalValues );
						self::getStorage( 'global_values' )->store( $globalValues );
					}
				//	self::getStorage( 'global_values' )->store( array() );
					
					$stageCompleted = true;
					$this->_values = array();
					$this->_stageCompleted = true; 	//	Refresh submission
					
				//	--$i; //	Step backward
				//	if( count( $values ) !== count( $this->_fieldsets ) )
					{
						continue;
					}
				//	$each = null; //	Don't include this again
				}
			}
			$this->_stageCompleted = false; 	//	Refresh submission
			$form .= $each;
		//	var_export(  );
		//	$form .= "<p><input type='image' value='Refresh Form'/></p>";
			$i++;
			//	continue; break;
		}
//		if( ! $this->getOneFieldSetAtATime() || ! $values['oneFieldSetAtATime'] )
		if( ! $this->getParameter( 'no_required_fieldset' ) )   
		{
			$form .= $this->getElementMarkup( $this->getRequiredFieldset() );
		}
	//	var_export( $each );
	//	var_export( $values['oneFieldSetAtATime'] );
	//	self::v( $values );
	

		if( $this->isSubmitted() )
		{
			if( $this->_badnews ){ $this->_values = array(); }
			if( $this->getOneFieldSetAtATime() &&  ! $this->_badnews )
			{ 
				//	To save required elements values - not working
				$tempValues = $this->_values ? : array();
				$this->_values = array(); 
			//	var_export( $tempValues );
				if( count( @$values['oneFieldSetAtATime'] ) === count( $this->_fieldsets ) )
				{
				//	var_export( $tempValues );
				//	var_export( __LINE__ );
					unset( $values['oneFieldSetAtATime'] ); //	disturbing my db
					unset( $values[''] ); //	disturbing my db
					$this->_values = $values + $tempValues;
					self::getStorage( $this->_attributes['id'] )->clear();
				//	self::getStorage( 'global_values' )->clear();
				}
			}
		}
		
		
	//	self::getStorage( $this->_attributes['id'] )->clear();
		if( $this->getOneFieldSetAtATime() && @$values['oneFieldSetAtATime'] )
		{
		//	$form .= '<button rel="ignore" onclick="var result=confirm( \'Do you want to go back to the previous stage?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" name="' . self::BACKBUTTON_INDICATOR . '" value="<"><img height="25" src="/img/backbutton.png" title="Go back to previous form fields" alt="<"></button>';
			$form .= '<input class="pc-btn pc-btn-small" rel="ignore" onclick="var result=confirm( \'Do you want to go back to the previous stage?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" title="Go back to previous form fields" name="' . self::BACKBUTTON_INDICATOR . '" value="&laquo;  Back ">';
			
		//	$form .= '<button rel="ignore" onclick="var result=confirm( \'Do you want to start all over?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" name="' . self::REFRESH_INDICATOR . '" value="o"><img height="25" src="/img/reload.png" title="Start form all over" alt="o"></button>';
			$form .= '<input class="pc-btn pc-btn-small" rel="ignore" onclick="var result=confirm( \'Do you want to start all over?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" name="' . self::REFRESH_INDICATOR . '" value="&laquo;&laquo; Start all over " title="Start form all over"/>';     
		}
			//		var_export( $values );
		//			var_export( count( $values['oneFieldSetAtATime'] ) );
			//	var_export( count( $this->_fieldsets ) );
		if( $this->callToAction )
		{
			$form .= '</span>';
		}
		
		if( ! $this->getParameter( 'no_form_element' ) )
		{
			$form .= "</form>\n ";
		}
		$form .= $this->wrapForm ? "</div>\n" : null;
		
		//	ON COMPLETION, EXECUTE THE REQUIREMENTS
		if( $this->_values )
		{
			unset( $this->_values[''] ); //	disturbing my db
			foreach( $this->getFormRequirements() as $class )
			{
				$parameters = array( 'fake_values' => $this->_values );
				$class->setParameter( $parameters );
				$class->fakeValues = $this->_values;
				$class->init();
			}
		}
		
		//	MERGE VALUES WITH GLOBAL REQUEST VALUES
	//	$_REQUEST = array_merge( $_REQUEST, $this->_values );
			//		var_export( $this->_values );
			//		var_export( $form );
		return $this->_form = $form;
    }
	
    /**
     * Returns the markup for each element
     *
     * @param Ayoola_Form_Element
     * @return string
     */		
    public function getElementMarkup( $fieldset )
    {
	//	self::v( $this->_global );
	//	self::v( $this->_names );
		$form = null;
		$form .= @$fieldset->container ? "<{$fieldset->container}>\n" : null;
		$form .= $fieldset->getLegend() && ! $this->getParameter( 'no_fieldset' ) ? "<fieldset>\n" : null;
		$form .= $fieldset->getLegend() ? "\n<legend>{$fieldset->getLegend()}</legend>\n" : null;
		$allElements = $fieldset->getElements();
/* 		if( $this->getParameter( 'return_required_fieldset_values' ) )
		{
			array_push( $allElements, $this->getRequiredFieldset() );
		}
 */		$elementMarkups = null;
		if( ! empty( $_REQUEST['pc_form_element_whitelist'] ) )
		{
			$whiteList = array_map( 'trim', explode( ',', $_REQUEST['pc_form_element_whitelist'] ) );
		}
		else
		{
			$whiteList = $this->getParameter( 'element_whitelist' );
		}
		foreach( $allElements as $name => $markup )
		{
		//	var_export( $name . '<br>' );
		//	var_export( $this->_names[$name]['real_name'] . '<br>' );
			//	If we have a whitelist, we only want to see some elements and discard the rest
			if( ! empty( $this->_names[$name]['data-pc-ignore-field'] ) )
			{
				//	You are always on the whitelist
			}   
			elseif( $whiteList && ! in_array( $this->_names[$name]['real_name'], $whiteList ) && ! in_array( @$this->_names[$name]['data-pc-element-whitelist-group'], $whiteList ) )       
			{
			//	var_export( $this->_names[$name]['real_name'] );
			//	var_export( @$this->_names[$name]['data-pc-element-whitelist-group'] );  
			//	self::v( $this->getParameter( 'element_whitelist' ) );
				continue;
			}
			else
			{
			//	self::v( $this->_names[$name]['real_name'] );
			//	self::v( $this->_names[$name]['real_name'] );
			}
			if( $this->isSubmitted() )
			{
			//	if( $this->fakeValues[$this->_names[$name]['real_name']] ){ $this->_values = array(); }
			//	var_export( $this->_global[$name] );
			//	var_export( $this->_global[$name]['real_name'] );
		//		var_export( $this->fakeValues );
		//		var_export( $this->fakeValues[$this->_names[$name]['real_name']] );
				if( empty( $this->_names[$name]['data-pc-ignore-field'] ) )
				{
			//		var_export( $this->_names[$name]['data-pc-ignore-field'] );
					
					$this->_values[@$this->_names[$name]['real_name']] = isset( $this->_global[$name] ) ? $this->_global[$name] : @$this->_global[@$this->_names[$name]['real_name']];
				//	var_export( $name . '<br>' );
			//		var_export( $this->_names[$name]['real_name'] . '<br>' );
				//	var_export( @$this->_names[$name]['real_name'] );
				//	var_export( $this->_values[@$this->_names[$name]['real_name']] );
				//	var_export( $this->_global[$name] );
					$this->_filter( $name );
					$this->_validate( $name );     
				}
			}
			
			
	//		$form .= "<div style='display:inline;'>\n";
			$replace = null;
			if( 
					$this->badnewsPerElement 
				&& ( @$this->_badnews[$name] || @$this->_badnews[@$this->_names[$name]['real_name']] )
				&& ( isset( $this->_global[$name] ) || isset( $this->_global[@$this->_names[$name]['real_name']] ) || isset( $this->_names[$name]['required'] ) ) 
				)
			{
				$this->_badnews[$name] = @$this->_badnews[$name] ? : @$this->_badnews[@$this->_names[$name]['real_name']];
			//	$form .= "<span style='display:block' class='badnews'>{$this->_badnews[$name]}</span>\n";
		//		$form .= "<span style='display:inline-block;' class='badnews'>{$this->_badnews[$name]}</span>\n";
			//	$this->_badnews[] = $this->_badnews[$name];
			//	unset( $this->_badnews[$name] );
			//	self::$_checkValues = true;
				$replace = "<div style='margin-top:0.5em;margin-bottom:0.5em' class='badnews'>{$this->_badnews[$name]}</div>\n";

			}
			$elementMarkups .= str_ireplace( self::$_placeholders['badnews'], $replace, $markup );
		//	$form .= $markup;
//			$form .= "</div>\n";    
		}
		$form .= Ayoola_Object_Wrapper_Abstract::wrap( $elementMarkups, @$fieldset->wrapper  );
		$form .= $fieldset->allowDuplication ? "<div style='display:inline-block;'><a class='pc-btn pc-btn-small' href='javascript:' title='" . ( @$fieldset->duplicationData['add'] ? : "Duplicate this fieldset" ) . "' onClick='try{ ayoola.xmlHttp.callAfterStateChangeCallbacks(); }catch( e ){}var fieldset = this.parentNode.parentNode.cloneNode( true ); var fieldtags= [ \"input\", \"textarea\", \"select\"]; for ( var tagi= fieldtags.length; tagi-->0; ) { var fields = fieldset.getElementsByTagName( fieldtags[tagi] ); for( var i = fields.length; i-->0; ){ fields[i].value= \"\"; } } this.v.parentNode.parentNode.insertBefore( fieldset, this.parentNode.parentNode.nextSibling ); ayoola.xmlHttp.callAfterStateChangeCallbacks(); this.name=\"\"; ayoola.div.refreshVisibleCounter(\"" . @$fieldset->duplicationData['counter'] . "\");'>" . ( @$fieldset->duplicationData['add'] ? : " + " ) . "</a>\n" : null; 
		$form .= $fieldset->allowDuplication ? "<a class='pc-btn pc-btn-small' href='javascript:' title='" . ( @$fieldset->duplicationData['add'] ? : "Remove this fieldset" ) . "' onClick='confirm( \"Delete all the elements in these fieldset?\") ? this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode ) : null; ayoola.div.refreshVisibleCounter(\"" . @$fieldset->duplicationData['counter'] . "\");'>" . ( @$fieldset->duplicationData['remove'] ? : " - " ) . "</a></div>\n" : null; 
	//	var_export( $div );  
	//	var_export( $this->_values );
	//	$form .= $fieldset->allowDuplication ? "<button title='Duplicate this fieldset' onClick='this.parentNode.parentNode.insertBefore( this.parentNode.cloneNode( true ), this.parentNode );'>+</button>\n" : null;
	//	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
		$form .= $fieldset->getLegend() && ! $this->getParameter( 'no_fieldset' ) ? "</fieldset>\n" : null;
		$form .= @$fieldset->container ? "</{$fieldset->container}>\n" : null;
		return $form;  
    }
	
    /**
     * Return $_global
     *
     * @param void
     * @return array
     */		
    public function getGlobal()
    {
		if( $this->_global )
		{
//			return $this->_global;
		}
		//	Determine the method
//		var_export( $this->_attributes );
/* 		$values = self::getStorage( 'global_values' )->retrieve();
		if( $values )
		{
			$this->_global = array_merge( $this->_global, $values );
		}
 */		switch( strtoupper( @$this->_attributes['method'] ) )
		{
			case 'GET':
				$this->_global = array_merge( $this->_global, $_GET );
			break;
			default:
				$this->_global = array_merge( $this->_global, $_POST );
			break;
		}
		if( $this->fakeValues )
		{
//		var_export( $this->_global );
			$this->_global = array_merge( $this->_global, $this->fakeValues );
		}
	//	var_export( $this->fakeValues );
        return $this->_global;
    }
	
    /**
     * Return values
     *
     * @param void
     * @return array
     */		
    public function getValues()
    {
        return $this->_values;
    }
	
    /**
     * Return the form error
     *
     * @param void
     * @return array
     */			
    public function getBadnews()
    {
        return $this->_badnews;
    }
	
    /**
     * Sets Error Message
     *
     * @param string Error Message
     * @return string field name
     */			
    public function setBadnews( $badnews, $name = null )
    {
		$this->_badnews[$name] = $badnews;
    }
	
    /**
     * Checkes if form has been submitted
     *
     * @return bool
     */			
    public function isSubmitted()
    {
//		var_export( $this->fakeValues );
		if( $this->fakeValues )
		{
			return true;	// Fake values submission is always true
		}
		$name = self::HONEY_POT;
		$name = self::hashElementName( $name ); // Honey Pots should always be hashed

 		if( isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) )
		{
			return false;
		}
 		$name = $this->_attributes['name'] . self::SUBMIT_DETECTOR;
		if( @$_REQUEST[$name] === $this->_attributes['name'] )
		{
			return true;
		}
		$name = self::hashElementName( $name ); // Try see if it is hashed
		if( @$_REQUEST[$name] === $this->_attributes['name'] )
		{
			return true;
		}
		$formOptions =  Application_Settings_Abstract::getSettings( 'Forms', 'options' ); 
//		$formOptions =  Application_Settings_Abstract::getSettings( 'Forms' ); 
//		var_export(  $formOptions );
		if( is_array( $formOptions ) && in_array( 'allow_external_form_values', $formOptions )  )
		{
		//	var_export(  $this->_attributes['name'] );
			if( 
				( ! empty( $_REQUEST['form_name'] ) && $_REQUEST['form_name'] === $this->_attributes['name'] ) 
				|| ( ! empty( $_POST ) && Ayoola_Application::isClassPlayer() )
			)
			{
				$this->oneFieldSetAtATime = false;
				return true;
			}		
		}
		return false;
    }

    public function addFieldset( Ayoola_Form_Element $elements )
    {
	//				var_export( __LINE__ );

	//	$fieldsetId = md5( serialize( $elements ) );
		//	Import the useful properties from elements
		//	Dont add empty fieldset
		if( $elements->getNames() )
		{
			if( $elements->id )
			{
				//	disable adding fieldset twice
				if( isset( $this->_fieldsets[$elements->id] ) ){ return false; }
				$this->_fieldsets[$elements->id] = $elements;
			}
			else
			{
				$this->_fieldsets[] = $elements;
			}
		}
		
		$this->_names = $this->_names ? :$this->getRequiredFieldset()->getNames() ;
		$this->_names = array_merge( $elements->getNames(), $this->_names );
	//	$this->_values = array_merge( $elements->getValues(), $this->_values );
		$this->_requirements = array_merge( $elements->getRequirements(), $this->_requirements );
		$this->_filters = array_merge( $elements->getFilters(), $this->_filters );
    }
	
    /**
     * Loads fieldset into form
     * 
     * @param array Fieldset
     * @return null
     */
    public function addFieldsets( array $fieldsets )
    {
		foreach( $fieldsets as $each )
		{
			$this->addFieldset( $each );
		}
    }
	
    /**
     * Returns all the fieldsets in the form
     * 
     * @param void
     * @return array
     */
    public function getFieldsets()
    {
		return $this->_fieldsets;
    }
	
    /**
     * Sets _defaultValues
     * 
     * @param array
     * @return null
     */
    public function setDefaultValues( array $values )
    {
		self::$_defaultValues += $values;
    }
	
    /**
     * Gets _defaultValues
     * 
     * @param array
     * @return null
     */
    public function getDefaultValues( $key = null )
    {
		return $key ? @self::$_defaultValues[$key] : self::$_defaultValues;
    }
	
    /**
     * Returns all the fieldsets in the form
     * 
     * @param array
     * @return null
     */
    public function setAttributes( array $attributes )
    {
		$this->_attributes += $attributes;
    }
	
    /**
     * Checks if we want oneFieldSetAtATime
     * 
     * @param void
     * @return bool
     */
	public function getOneFieldSetAtATime()
    {
		if( $this->getParameter( 'element_whitelist' ) || ! empty( $_REQUEST['pc_form_element_whitelist'] ) )
		{
			return false;
		}
		return $this->oneFieldSetAtATime;
    }
	
    /**
     * Returns the storage for oneFieldSetAtATime
     * 
     * @param string Unique ID for Namespace
     * @return Ayoola_Storage
     */
	public static function getStorage( $id )
    {
		$storage = new Ayoola_Storage(); 
		$storage->storageNamespace = __CLASS__ . $id;
		return $storage;
    }
	
    /**
     * Hashes the element name as an antibot measure
     * 
     * @param string The name of the element
     * @return string The hashed element
     */
	public static function hashElementName( $name )
    {
	//	$a = range( 'a', 'z' );
	//	shuffle( $a );
		return ltrim( Ayoola_Captcha::getHash( array( 'name' => $name ) ), '0123456789' );
    }
	
    /**
     * Retrieves the global value of an element
     * 
     * @param string The name of the element
     * @param string Value to return if other values are unavailable
     * @return mixed The global value of the element
     */
	public static function getGlobalValue( $name, $defaultValue = null, $ignoreSessionValues = false )
    {
//		var_export( $_REQUEST[$name] );
	//	var_export( $_REQUEST[Ayoola_Form::hashElementName( $name )] );
	//	$value =  ! is_null( $defaultValue ) ? $defaultValue : false;
		$value =  false;
	//	if( isset( $this->fakeValues[$name] ) )
		{
	//		$value = $this->fakeValues[$name];
		}
		if( isset( $_REQUEST[$name] ) )
		{
			$value = $_REQUEST[$name];
		}
		elseif( isset( $_REQUEST[Ayoola_Form::hashElementName( $name )] ) )
		{
			$value = $_REQUEST[Ayoola_Form::hashElementName( $name )]; 
		}
		elseif( $defaultValue )
		{
	//		$value = $defaultValue;
		}

		if( $value === false && ! $ignoreSessionValues )
		{
			$values = self::getStorage( 'global_values' )->retrieve();
		//	var_export( $values );
		//	if( $name == 'page_options' )
			{
			//	self::v( $name );
			//	self::v( $defaultValue );
			}
			if( is_null( $defaultValue ) && isset( $values[$name] )  )
			{
		//	$defaultValue ? : var_export( $values );
				$value = $values[$name];
			}
		}
		
		//	register this in the global var if its in the session.
	//	if( isset( $values[$name] ) )
		{
			//	Removed because its making the first value sticky
		//	$_REQUEST[$name] = $value;
		}
		if( false === $value && ! is_null( $defaultValue ) )
		{
			$value = $defaultValue;
		}
		return $value;
    }
	
    /**
     * View the form
     * 
     * @return string
     */	
    public function view()
    {
		$form = null;
	//		var_export( $this->_badnews );
		if( $this->badnewsBeforeElements && $this->_badnews )
		{
			$form .= '<ul>';
			foreach( $this->_badnews as $message ) 
			{
				$form .= "<li class='badnews'>$message</li>\n";
			}
			$form .= '</ul>';
		}
	//		var_export( $this->_badnews );
		$form .= $this->getForm();
	//		var_export( $this->_badnews );
		return $form;
    }
}
