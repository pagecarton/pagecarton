<?php
/**
 * PageCarton
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
	public $badnewsBeforeElements = false;

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
     * Switch to true to load each fieldset at a time
     * 
     * @var boolean 
     */					
	public $oneFieldSetAtATimeJs = false;  

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
		$this->_attributes['id'] = @$this->_attributes['id'] ? : @$this->_attributes['name'] . '_form_id';

		$this->_attributes['class'] = @$this->_attributes['class'] ? : 'pc-form2';

		$this->_attributes['action'] = @$this->_attributes['action'] ? : '#' . $this->_attributes['id'];


    }

    /**
     * Filters the values
     *
     * @param void
     * @return boolean
     */
    protected function _filter( $name )
    {	

		if( ! @is_array( $this->_filters[$name] ) ){ return false; }

		//	Filters values before validation
		$filter = 'Ayoola_Filter_';
		foreach( $this->_filters[$name] as $seive => $parameter )
		{
			$requiredFilter = $filter . ucfirst( $seive );
			if( ! Ayoola_Loader::loadClass( $requiredFilter ) )
			{ 
				continue;

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

				$this->_values[$this->_names[$name]['real_name']] = $process->filter( $this->_values[$this->_names[$name]['real_name']] );

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
 		//	Extra check for non-admin
		$allowedCoders =  Application_Settings_Abstract::getSettings( 'Forms', 'coders_access_group' ); 

		//	<a>efewwewe</a>
	//	if( $allowedCoders && ! Ayoola_Form::hasPriviledge( $allowedCoders ) )

		//	first fight against xss
		if( ! Ayoola_Form::hasPriviledge( $allowedCoders ? : 98 ) )
		{
			//	Turning this to array allows to validate array values
			$values = $this->_values[$this->_names[$name]['real_name']];
			$values = is_array( $values ) ? $values : array( $values );

			foreach( $values as $each )
			{

				if( $each != strip_tags( $each ) )  
				{

					//	Notify Admin

					$mailInfo['body'] = 'Alert! Code Injction by user
					Info: ' . var_export( $this->_values, true ) . '.
					';
					Application_Log_View_Error::log( $mailInfo['body'] );
					$this->setBadnews( 'Codes are not allowed in forms!', $name );
					return false;
				} 
			}
		}
		elseif( ! Ayoola_Form::hasPriviledge( 98 ) )
		{
			//	Turning this to array allows to validate array values
			$values = $this->_values[$this->_names[$name]['real_name']];

			if( is_array( $values ) )
			{
				foreach( $values as $key => $each )
				{
					$values[$key] = self::cleanHTML( $values[$key] );
				}
				$this->_values[$this->_names[$name]['real_name']] = $values;
			}
			else
			{
				$this->_values[$this->_names[$name]['real_name']] = self::cleanHTML( $values );
			}

		}

		if( ! @is_array( $this->_requirements[$name] ) ){ return true; }
		$validator = 'Ayoola_Validator_';
		foreach( $this->_requirements[$name] as $requirement => $parameter )
		{ 
			$requiredValidator = $requirement;
			if( ! Ayoola_Loader::loadClass( $requirement ) )
			{ 
				$requiredValidator = $validator . ucfirst( $requirement );
				if( ! Ayoola_Loader::loadClass( $requiredValidator ) )
				{ 
					//	invalid validator means its always invalid
					return false;

				}
			}
			if( ! new $requiredValidator instanceof Ayoola_Validator_Interface )
			{
				return false;
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

			foreach( $values as $each )
			{

				if( ! $check->validate( $each ) )
				{	
					$label = $this->_names[$name]['label'] ? : 'This field';
					$badnews = str_ireplace( array( '%value%', '%variable%' ), array( '"' . $label . '"', ( is_scalar( $each ) ? $each : null ) ), isset( $parameter['badnews'] ) ? $parameter['badnews'] : $check->getBadNews() );

					if( isset( $this->_names[$name]['optional'] ) )
					{

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
		$sessionTime = time() - intval( $_SESSION['PC_SESSION_START_TIME'] );
		$delay =  Application_Settings_Abstract::getSettings( 'Forms', 'session_delay_time' );
		if( isset( $this->_attributes['name'] ) && $this->_attributes['name'] === 'Ayoola_Access_Login' )
		{
			//	don't delay on login
			//sleep( 30 );
		}
		elseif( ! self::hasPriviledge( array( 99, 98 ) ) && $delay > $sessionTime )
		{
			$this->setBadnews( 'Please wait ' . ( $delay - $sessionTime ) . ' secs before submitting your form!', '' );
		}
        $element = new Ayoola_Form_Element;
		$element->useDivTagForElement = false;

		if( $this->useCaptcha )
		{
			$element->addElement( 'name=>captcha:: type=>Captcha' );
			$element->addRequirement( 'captcha','Captcha' );
        }

        $submitDetector = @$this->_attributes['name'] . self::SUBMIT_DETECTOR;
        $authCode = self::hashElementName( $this->_attributes['name'] );

        $url = '' . Ayoola_Application::getUrlPrefix() . '/tools/classplayer/get/name/Ayoola_Form?form=' . $this->_attributes['name'] . '&auth=' . $authCode;
        Application_Javascript::addCode(
            '
                ayoola.events.add( window, "load", function()
                {
                    var fx = document.getElementsByName( "' . $submitDetector .'" )[0];
                    fx = fx ? fx : document.getElementsByName( "' . self::hashElementName( $submitDetector ) .'" )[0];

                    if( ! fx )
                    {
                        return false;
                    }
                    var ajax = ayoola.xmlHttp.fetchLink( { url: "' . $url . '" , noSplash: true } );
                    var activateForm = function()
                    {
                        if( ayoola.xmlHttp.isReady( ajax ) )
                        {	
                            //  just a funny trick. Not fully implemented yet.
                            //  Put off bots that cannot run JS
                            //  In the future, we will put a real auth here
                            fx.value = "' . $authCode .'";
    
                        }		
                    }
                    ayoola.events.add( ajax, "readystatechange", activateForm );
    
                });

	
            '
        );

		if( $this->submitValue && ! $this->callToAction )
		{

			$element->addElement( array( 'name' => 'pc-submit-button', 'value' => '' . self::__( $this->submitValue ) . '  <i class="fa fa-chevron-right pc_give_space "></i> ', 'type' => 'SubmitButton', 'onclick' => 'this.form.submit(); this.value=\'Loading...\'; this.disabled=true; return false;', 'style' => 'display:block;margin-top:1.5em;', 'class' => 'pc-btn', 'data-pc-ignore-field' => 'true' ) );    
		}
		foreach( $this->requiredElements as $key => $value )
		{

			$element->addElement( array( 'name' => $key, 'value' => $value, 'type' => 'Hidden' ) );
		}
        $element->addElement( array( 'name' => $submitDetector, 'value' => @$this->_attributes['name'], 'type' => 'Hidden', 'data-pc-ignore-field' => 'true' ) );
        $element->addElement( array( 'name' => self::HONEY_POT, 'type' => 'HoneyPot', 'value' => '', 'data-pc-ignore-field' => 'true' ) );

		$element->addFilters( 'Trim:: Escape::Alnum' );  
		$this->requiredFieldSet = $element; 
		return $this->requiredFieldSet;
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

		//	ADD FORM REQUIREMENTS
		$requirements = is_string( $requirements ) ? array_map( 'trim', explode( ',', $requirements ) ) : $requirements;

		$requirements = is_array( $requirements ) ? $requirements : array();
		if( ! $requirements ){ return $requirements; }

		//	Test the first element to detect if each content is an array of ready content
		$a = $requirements;
		@$a = array_shift( $a );
		$table = Ayoola_Form_Requirement::getInstance();

		switch( gettype( $a ) )
		{
			case 'string':
				$requirements = $table->select( null, array( 'requirement_name' => $requirements ) );
			break;
		}

		if( $requirements )
		{

        }
		foreach( $requirements as $each )  
		{

			switch( gettype( $each ) )
			{
				case 'object':
					$class = $each;
					if( ! method_exists( $class, 'createForm' ) ){ continue 2; }
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

					if( ! empty( $each['requirement'] ) )
					{
						$b = $table->selectOne( null, array( 'requirement_name' => $each['requirement'] ) );
						$combinedInfo = array_merge( $b, $each );
					}

					$class = @$combinedInfo['requirement_class'];	 

					if( ! Ayoola_Loader::loadClass( $class ) )
					{
						continue 2;
					}

					$class = new $class( @$combinedInfo['parameters'] );

					if( ! method_exists( $class, 'createForm' ) ){ continue 2; }
					$this->_formRequirements[] = $class;
					$fieldsets = $class->getForm()->getFieldsets();
					$this->actions += $class->getForm()->actions;
					foreach( $fieldsets as $key => $fieldset )
					{
						$fieldset->appendElement = false;
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

		if( null === $this->_form ){ $this->setForm( $formOptions ); }	
        return $this->_form;
    }

    /**
     * Creates the form
     *
     */		
    public function setForm( array $formOptions = null )
    {

		if( is_array( @$_POST['Ayoola_Form'][$this->formNamespace]['fake_values'] ) )
		{
			$this->fakeValues = $_POST['Ayoola_Form'][$this->formNamespace]['fake_values'];

		}
		if( $this->getOneFieldSetAtATime() && isset( $_POST['Ayoola_Form'][$this->formNamespace]['one_field_set_at_a_time'] ) )
		{
			$this->oneFieldSetAtATime = $_POST['Ayoola_Form'][$this->formNamespace]['one_field_set_at_a_time'];

		}

		$this->_attributes = $this->_attributes ? array_merge( $this->_defaultAttributes, $this->_attributes ) : $this->_defaultAttributes;
		$this->_attributes['id'] .= $this->formNamespace;

		$form = null;
		$form .= $this->wrapForm ? "<div id='{$this->_attributes['id']}_container'>\n" : null;
		if( $this->oneFieldSetAtATimeJs )
		{
			$this->_attributes['class'] .= ' pc-form-one-fieldset-at-a-time';

		}
		if( ! $this->getParameter( 'no_form_element' ) )
		{

			$form .= '<form data-pc-form=1 ';   
			foreach( $this->_attributes as $key => $value )
			{
				$form .= empty( $this->_attributes[$key] ) ? null : " $key='$value' ";  
			}

			$this->getGlobal();	//	Load the global
			$form .= ">\n";
		}
		$i = 0;
		if( $this->getOneFieldSetAtATime() )
		{
			$values = self::getStorage( $this->_attributes['id'] )->retrieve();

			//	So we can also have this as global values like get and post

			$values = is_array( $values ) ? $values : array();

			//	So we can also have this as global values like get and post

			if( isset( $_REQUEST[self::REFRESH_INDICATOR] ) || in_array( self::REFRESH_INDICATOR, $this->actions ) )
			{

				self::getStorage( $this->_attributes['id'] )->clear();
				$_REQUEST[self::REFRESH_INDICATOR] = true;

			}
			elseif( isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) || in_array( self::BACKBUTTON_INDICATOR, $this->actions ) )
			{
				$_REQUEST[self::BACKBUTTON_INDICATOR] = true;
				@array_pop( $values['oneFieldSetAtATime'] );

				self::getStorage( $this->_attributes['id'] )->store( $values );

			}
		}

		//	ADD POST-FORM REQUIREMENTS
		$this->setFormRequirements( $this->getParameter( 'requirements' ) );

		if( @$values )
		{
			//	form filling in progress, no need for call to action
			$this->callToAction = false;
		}
		if( $this->callToAction )
		{

			//	Show call to action, disable all elements
			$form .= $this->callToAction;
			$form .= '<span style="display:none;">';

		}

		//	Fieldset groups
		$group = null;
		foreach( $this->_fieldsets as $fieldsetId => $fieldset )
		{	

			if( $this->getOneFieldSetAtATime() )
			{
				$values = self::getStorage( $this->_attributes['id'] )->retrieve();

				$values = is_array( $values ) ? $values : array();

				if( @$values['oneFieldSetAtATime'][$fieldsetId] )
				{
					//	Stage already completed
					continue;
				}
				elseif( $i )
				{
					continue;
				}

			}

			$each = $this->getElementMarkup( $fieldset );

			if( $this->isSubmitted() && ! $this->_badnews && ! $this->_stageCompleted )
			{
				if( $this->getOneFieldSetAtATime() && ! isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) && ! isset( $_REQUEST[self::REFRESH_INDICATOR] ) )
				{

					$values['oneFieldSetAtATime'][$fieldsetId] = true;
					$values = array_merge( $values, $this->_values );
					self::getStorage( $this->_attributes['id'] )->store( $values );

					//	So we can also have this as global values like get and post

					//	RE-IMPLEMENTING SESSION GLOBAL VALUES TO ACT AS PRESET VALUES IN OTHER FIELDS
					//	So we can also have this as global values like get and post
					$globalValues = self::getStorage( 'global_values' )->retrieve() ? : array();

					$globalValues = $this->_values + $globalValues;
					if( $this->_values )
					{

						list( $globalValues ) = array_chunk( $globalValues, 100, true );

						self::getStorage( 'global_values' )->store( $globalValues );
					}

					$stageCompleted = true;
					$this->_values = array();
					$this->_stageCompleted = true; 	//	Refresh submission

					continue;

                }
                else
                {
                    self::getStorage( 'global_values' )->store( $this->_values );
                }
			}
			$this->_stageCompleted = false; 	//	Refresh submission
			$form .= $each;

			$i++;

		}
		if( ! $this->getParameter( 'no_required_fieldset' ) )   
		{
			$form .= $this->getElementMarkup( $this->getRequiredFieldset() );
		}

		if( $this->isSubmitted() )
		{
			if( $this->_badnews ){ $this->_values = array(); }
			if( $this->getOneFieldSetAtATime() &&  ! $this->_badnews )
			{ 
				//	To save required elements values - not working
				$tempValues = $this->_values ? : array();
				$this->_values = array(); 
				if( ! empty( @$values['oneFieldSetAtATime'] ) && count( @$values['oneFieldSetAtATime'] ) === count( $this->_fieldsets ) )
				{
					unset( $values['oneFieldSetAtATime'] ); //	disturbing my db
					unset( $values[''] ); //	disturbing my db
					$this->_values = $values + $tempValues;
					self::getStorage( $this->_attributes['id'] )->clear();
				}
			}
		}

		if( $this->getOneFieldSetAtATime() && @$values['oneFieldSetAtATime'] )
		{
			$form .= '<input class="pc-btn pc-btn-small" rel="ignore" onclick="var result=confirm( \'Do you want to go back to the previous stage?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" title="Go back to previous form fields" name="' . self::BACKBUTTON_INDICATOR . '" value="&laquo;  Back ">';

			$form .= '<input class="pc-btn pc-btn-small" rel="ignore" onclick="var result=confirm( \'Do you want to start all over?\' );result?this.setAttribute( \'rel\', \'\' ):null; return result;" type="submit" name="' . self::REFRESH_INDICATOR . '" value="&laquo;&laquo; Start all over " title="Start form all over"/>';     
		}
		if( $this->callToAction )
		{
			$form .= '</span>';
		}
		if( $this->oneFieldSetAtATimeJs )
		{
			Application_Javascript::addCode( 
				'pc_ShowNextFieldset = function( form )
				{
					var a = form.getElementsByTagName( "fieldset" );
					var foundNext = false;

					for( var b = 0; b < a.length; b++ )
					{
						if( a[b].getAttribute( "data-pc-form-done-fieldset" ) == "true" || a[b].className == "pc-form-fieldset-1" )
						{
							if( b + 2 == a.length )
							{
								break;
							}
							a[b].style.display = "none";
							continue;
						}
						if( a[b].className == "pc-form-fieldset-" + ( b + 1 ) )
						{
							foundNext = true;
							a[b].style.display = "block";
							var c;
							if( c = a[b].getElementsByTagName( "input" ) )
							{
								c[0] ? c[0].focus() : null;
							}
							a[b].setAttribute( "data-pc-form-done-fieldset", "true" )
							break;
						}
						else
						{

						}
					}
					if( ! foundNext )
					{

						form.submit();
					}
				}'
			 );
			$form .= '<a onclick="pc_ShowNextFieldset( this.parentNode );" class=" pc-btn" href="javascript:">Continue...</a>';

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
		@++$this->counter;
		$form = null;
		$form .= @$fieldset->getPreHtml();
		$fieldsetTag = @$fieldset->tag ? : "fieldset";
		$form .= ! @$fieldset->noFieldset && ! $this->getParameter( 'no_fieldset' ) ? "<{$fieldsetTag} class='pc-form-fieldset-{$this->counter}'>\n" : null;
		$allElements = $fieldset->getElements();
		$elementMarkups = null;
		if( ! empty( $_REQUEST['pc_form_element_whitelist'] ) )
		{
			$whiteList = array_map( 'trim', explode( ',', $_REQUEST['pc_form_element_whitelist'] ) );
		}
		else
		{
			$whiteList = $this->getParameter( 'element_whitelist' );
        }
		foreach( $allElements as $elementX )
		{
			foreach( $elementX as $name => $markup )
			{
	
				//	If we have a whitelist, we only want to see some elements and discard the rest
				if( ! empty( $this->_names[$name]['data-pc-ignore-field'] ) )
				{
					//	You are always on the whitelist
				}   
				elseif( $whiteList && ! in_array( $this->_names[$name]['real_name'], $whiteList ) && ! in_array( @$this->_names[$name]['data-pc-element-whitelist-group'], $whiteList ) )       
				{
					continue;
				}
				if( $this->isSubmitted() )
				{
					if( empty( $this->_names[$name]['data-pc-ignore-field'] ) )
					{
						$this->_values[@$this->_names[$name]['real_name']] = isset( $this->_global[$name] ) ? $this->_global[$name] : @$this->_global[@$this->_names[$name]['real_name']];
						$this->_filter( $name );
						$this->_validate( $name );     
					}
				}
	
				$replace = '';
				if( 
						$this->badnewsPerElement 
					&& ( @$this->_badnews[$name] || @$this->_badnews[@$this->_names[$name]['real_name']] )
					&& ( isset( $this->_global[$name] ) || isset( $this->_global[@$this->_names[$name]['real_name']] ) || isset( $this->_names[$name]['required'] ) ) 
					)
				{
					$this->_badnews[$name] = @$this->_badnews[$name] ? : @$this->_badnews[@$this->_names[$name]['real_name']];
					$replace = "<div style='margin-top:0.5em;margin-bottom:0.5em' class='badnews'>{$this->_badnews[$name]}</div>\n";
	
				}
				$elementMarkups .= str_ireplace( self::$_placeholders['badnews'], $replace, $markup );
			}
	
		}
		$form .= Ayoola_Object_Wrapper_Abstract::wrapContent( $elementMarkups, @$fieldset->wrapper  );
		$form .= @$fieldset->getPostHtml();
		$form .= ! @$fieldset->noFieldset && ! $this->getParameter( 'no_fieldset' ) ? "</{$fieldsetTag}>\n" : null;
		return $form;  
    }

    /**
     * Return $_placeholders
     *
     * @param void
     * @return array
     */		
    public static function getPlaceholders()
    {
		return self::$_placeholders;
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

		}
		//	Determine the method
		switch( strtoupper( @$this->_attributes['method'] ) )
		{
			case 'GET':
				$this->_global = array_merge( $this->_global, $_GET );
			break;
			default:
				$this->_global = array_merge( $this->_global, $_POST ? : array() );
			break;
		}
		if( $this->fakeValues )
		{

			$this->_global = array_merge( $this->_global, $this->fakeValues );
		}

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
        if( $this->isSubmitted() && $this->_values )
        {
            //  refresh this to refresh ids
            // do this only once during the session
			// to combat spam, let us regenerate this once in the session
			// especially for users not signed in
			if( ! Ayoola_Application::getUserInfo() )
			{
				session_regenerate_id();
			}
        }
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

		if( $this->fakeValues )
		{
			return true;	// Fake values submission is always true
		}
		$name = self::HONEY_POT;
		$name = self::hashElementName( $name ); // Honey Pots should always be hashed

		//	honeypot should always be left blank
		if( ! empty( $_REQUEST[self::HONEY_POT] ) || ! empty( $_REQUEST[$name] ) )
		{
			return false;
		}

 		if( isset( $_REQUEST[self::BACKBUTTON_INDICATOR] ) )
		{
			
			return false;
		}
        $name = $this->_attributes['name'] . self::SUBMIT_DETECTOR;

         
        do
        {
            if( 
				@$_REQUEST[$name] === $this->_attributes['name'] 
				||  @$_REQUEST[self::hashElementName( $name )] === $this->_attributes['name']
				||  @$_REQUEST[self::hashElementName( $name )] === self::hashElementName( $this->_attributes['name'] )
			)
            {
                break;
            }
            $formOptions =  Application_Settings_Abstract::getSettings( 'Forms', 'options' ); 

            if( is_array( $formOptions ) && in_array( 'allow_external_form_values', $formOptions )  )
            {

                if( 
                    ( ! empty( $_REQUEST['form_name'] ) && $_REQUEST['form_name'] === $this->_attributes['name'] ) 
                    || ( ! empty( $_POST ) && Ayoola_Application::isClassPlayer() )
                )
                {
                    $this->oneFieldSetAtATime = false;
                    break;
                }		
            }

            return false;
        }
        while( false );
        return true;
    }

    public function addFieldset( Ayoola_Form_Element $elements )
    {

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

		$this->_names = $this->_names ? : $this->getRequiredFieldset()->getNames() ;
		$this->_names = array_merge( $elements->getNames(), $this->_names );

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
    public static function setDefaultValues( array $values )
    {
		self::$_defaultValues += $values;
    }

    /**
     * Gets _defaultValues
     * 
     * @param string
     * @return mixed
     */
    public static function getDefaultValues( $key = null )
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
     * Checks if we want oneFieldSetAtATime
     * 
     * @param void
     * @return bool
     */
	public function getNames()
    {
		return $this->_names;
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

		return ltrim( Ayoola_Captcha::getHash( array( 'name' => $name, 'daily' => false ) ), '0123456789' );
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

		$value =  false;
		{

		}
		if( isset( $_POST[$name] ) )
		{
			$value = $_POST[$name];
		}
		elseif( isset( $_POST[Ayoola_Form::hashElementName( $name )] ) )
		{
			$value = $_POST[Ayoola_Form::hashElementName( $name )]; 
		}
		elseif( $defaultValue )
		{

		}
		elseif( isset( $_REQUEST[$name] ) )
		{
			$value = $_REQUEST[$name];
		}
		elseif( isset( $_REQUEST[Ayoola_Form::hashElementName( $name )] ) )
		{
			$value = $_REQUEST[Ayoola_Form::hashElementName( $name )]; 
		}

		if( $value === false && ! $ignoreSessionValues )
		{
            $values = self::getStorage( 'global_values' )->retrieve();
			if( is_null( $defaultValue ) && isset( $values[$name] )  )
			{

				$value = $values[$name];
			}
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
		$formContent = $this->getForm();
		$form = null;

		if( $this->_badnews && self::isSubmitted() )
		{

			if( $this->badnewsBeforeElements )
			{
				foreach( array_unique( $this->_badnews ) as $message ) 
				{
					$form .= "<div class='badnews'>" . htmlspecialchars( $message ) . "</div>\n";
				}

			}
			elseif( $this->oneFieldSetAtATimeJs )
			{
				$form .= "<div class='badnews'>There are some errors in the form. Kindly go over it again</div>\n";
			}
		}
		$form .= $formContent;

		return $form;
    }
}
