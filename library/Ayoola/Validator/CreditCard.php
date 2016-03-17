<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_CreditCard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: CreditCard.php 24-02-2013 2.17pm ayoola $
 */

/**
 * @see Ayoola_Validator_Abstract
 */
 
require_once 'Ayoola/Validator/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Validator_CreditCard
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Validator_CreditCard extends Ayoola_Validator_Abstract
{
	
	var $__ccType = ''; 
	var $__ccNum = ''; 
	var $__ccExpM = 0; 
	var $__ccExpY = 0;
	var $__ccCVV = ''; 
	
    /**
     * Card Type to check against
     *
     * @var string
     */
	protected $_type;
	
    /**
     * This method does the main validation
     *
     * @param mixed
     * @return 
     */
    public function validate( $value )
    {
		$result = $this->isValid();
	//	var_export( $result );
		if( $result[0] == 'valid' )
		{
 			if( $this->_type && strtoupper( $this->_type ) != $result[1] )
			{
				$this->_badnews = 'Invalid ' . $this->_type; 
				return false;			
			}
 			return true;	
		}
		$this->_badnews = array_shift( $result ); 
		return false;
    } 
	
    /**
     * Returns the error message peculiar for this validation
     *
     * @param void
     * @return string
     */	
    public function getBadnews()
    {
        return $this->_badnews;
    }
	
	
    /**
     * Automated fill the parameters
     *
     * @param array
     * @return void
     */
	public function autofill( array $parameters )
    {
	//	var_export( $parameters );
		list( $num, $expm, $expy, $cvv, $type ) = $parameters;
		$this->_type = $type;
		$this->CCVal($num, $expm, $expy, $cvv);
    }
 
	/* The following came from http://www.pixelenvision.com/2314/php-credit-card-validation-class-using-mod-10-luhn-more/ */
	function CCVal($num, $expm, $expy, $cvv) 
	{
	 
		if(!empty($num)) 
		{ 
		  $cardNumber = preg_replace("[^0-9]", "", $num); 
		  if(!empty($cardNumber)) 
		  { 
			$this->__ccNum = $cardNumber; 
		  } 
		} 
	 
		if(!empty($cvv)) 
		{ 
		  $cardCVV = preg_replace("[^0-9]", "", $cvv); 
		  if(!empty($cardCVV)) 
		  { 
			$this->__ccCVV = $cardCVV; 
		  } 
		} 
		if(!is_numeric($expm) || $expm < 1 || $expm > 12) 
		{ 
		  $this->__ccExpM =NULL;
		} 
		else 
		{ 
		  $this->__ccExpM = strlen( $expm ) == 1 ? '0' . $expm : $expm; 
		} 
	 
		$currentYear = date('Y');
		settype($currentYear, 'integer');
		$expy = $expy > 2000 ? $expy : $expy + 2000;
		if(!is_numeric($expy) || $expy < $currentYear || $expy > $currentYear + 15) 
		{ 
		  $this->__ccExpY = NULL; 
		} 
		else 
		{ 
		  $this->__ccExpY = $expy; 
		}
	 
		if (preg_match("/^5[1-5][0-9]{14}$/", $this->__ccNum)) {
			$this->__ccType = 'MASTERCARD';}
		elseif (preg_match("/^4[0-9]{12}([0-9]{3})?$/", $this->__ccNum)) {
			$this->__ccType = 'VISA';}
		elseif (preg_match("/^3[47][0-9]{13}$/", $this->__ccNum)) {
			$this->__ccType = 'AMEX';}
		elseif (preg_match("/^3(0[0-5]|[68][0-9])[0-9]{11}$/", $this->__ccNum)) {
			$this->__ccType = 'DINNERS';}
		elseif (preg_match("/^6011[0-9]{12}$/", $this->__ccNum)) {
			$this->__ccType = 'DISCOVER';}
		elseif (preg_match("/^(3[0-9]{4}|2131|1800)[0-9]{11}$/", $this->__ccNum)) {
			$this->__ccType = 'JCB';}
		elseif (preg_match("/^(5[06-8]|6)[0-9]{10,17}$/", $this->__ccNum)) {
			$this->__ccType = 'MAESTRO';}
		else {
			$this->__ccType = 'UNKNOWN';}
	}
 
	function IsValid() 
	{ 
	 
	  $validFormat = false; 
	  $validExp = false;
	  $passCheck = false;
	  $validCVV = false;
	  $testCard = false;
	 
	switch($this->__ccType) 
	{ 
	  case 'MASTERCARD': 
		$validFormat = true;
		break; 
	case 'VISA': 
		$validFormat = true;
		break; 
	case 'AMEX': 
		$validFormat = true;
		break; 
	case 'DISCOVER': 
		$validFormat = true;
		break; 
	case 'DINNERS': 
		$validFormat = true;
		break; 
	case 'JCB': 
		$validFormat = true; 
		break; 
	case 'MAESTRO': 
		$validFormat = true; 
		break; 
	  default: 
	 
	  $validFormat = false; 
	}
 
	$cardExpM = $this->__ccExpM; 
	$cardExpY = $this->__ccExpY;
/* 	var_export( $cardExpM );
	var_export( $cardExpY );
	var_export( date('Ym') );
	var_export( $cardExpY.$cardExpM );
 */	if($cardExpM && $cardExpY && date('Ym')<=$cardExpY.$cardExpM) {
	$validExp = true;
	}
//	var_export( $validExp );
	 
	$cardCVV = $this->__ccCVV; 
	 
	if($cardCVV && (strlen($cardCVV)==3 && $this->__ccType!='AMEX')) {
	$validCVV = true;
	} elseif($cardCVV && (strlen($cardCVV)==4 && $this->__ccType=='AMEX')) {
	$validCVV = true;
	}
	 
	$cardNumber = strrev($this->__ccNum); 
	$numSum = 0; 
	 
	for($i = 0; $i < strlen($cardNumber); $i++) 
	{ 
	  $currentNum = substr($cardNumber, $i, 1); 
	 
	if($i % 2 == 1) 
	{ 
	  $currentNum *= 2; 
	} 
	 
	if($currentNum > 9) 
	{ 
	  $firstNum = $currentNum % 10; 
	  $secondNum = ($currentNum - $firstNum) / 10; 
	  $currentNum = $firstNum + $secondNum; 
	} 
	 
	$numSum += $currentNum; 
	}
	 
	$passCheck = ($numSum % 10 == 0);
	 
	$testCard = in_array($this->__ccNum,array('340000000000009','341111111111111','343434343434343','346827630435344','370000000000002','370000200000000','370407269909809','370556019309221','371449635398431','374200000000004','376462280921451','377752749896404','378282246310005','378734493671000','30000000000004','30569309025904','5019717010103742','30204169322643','30218047196557','30221511563252','36000000000008','36148900647913','36700102000000','38000000000006','38520000023237','6011000000000004','6011000000000012','6011000400000000','6011000990139424','6011111111111117','6011153216371980','6011601160116611','6011687482564166','6011814836905651','201400000000009','201481123699422','214925980592653','214983972181233','180001638277392','180040153546898','180058601526635','3528000700000000','3528723740022896','3530111333300000','3566002020360505','3569990000000009','630495060000000000','6304900017740292441','6333333333333333336','5100080000000000','5105105105105100','5111111111111118','5123619745395853','5138495125550554','5274576394259961','5301745529138831','5311531286000465','5364587011785834','5404000000000001','5424000000000015','5431111111111111','5454545454545454','5459886265631843','5460506048039935','5500000000000004','5500939178004613','5555555555554444','5565552064481449','5597507644910558','6334580500000000','6334900000000005','633473060000000000','6767622222222222222','6767676767676767671','5641820000000005','6331101999990016','6759649826438453','4007000000027','4012888818888','4024007127653','4222222222222','4556069275201','4556381812806','4911830000000','4916183935082','4916603452528','4929000000006','4005550000000019','4012888888881881','4111111111111111','4444333322221111','4539105011539664','4544182174537267','4716914706534228','4916541713757159','4916615639346972','4917610000000000','4406080400000000','4462000000000003','4462030000000000','4917300000000008','4917300800000000','4484070000000000','4485680502719433'));
	 
	  if($validFormat && $validExp && $validCVV && $passCheck && !$testCard) {
		  return array("valid",$this->__ccType,$this->__ccNum,$this->__ccCVV);
	  } else {
	  $ERR=NULL;
//	  var_export( $validFormat );
//	  var_export( $passCheck );
//	  var_export( $this->__ccNum );
	  if(!$validFormat || !$passCheck) $ERR[]="Invalid card number";
	  if(!$validCVV) $ERR[]="Invalid CVV2/CVC2 Number";
	  if(!$validExp) $ERR[]="Credit card has been expired";  
	  if($testCard) $ERR[]="Reserved card number";
	  return $ERR;
	  }
	} 
	 
	// END OF CLASS
}
