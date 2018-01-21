<?php
/**
 * PageCarton Content Management System
 *
 * LICENSE
 *
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: Image.php 10-26-2011 9.13pm ayoola $
 */

/**
 * @see Ayoola_Page_Editor_Abstract
 */
 
require_once 'Ayoola/Page/Editor/Abstract.php';


/**
 * @category   PageCarton CMS
 * @package    Ayoola_Page_Editor_Image
 * @copyright  Copyright (c) 2011-2016 PageCarton (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Page_Editor_Image extends Ayoola_Page_Editor_Text
{
 	
    /**
     * 
     * 
     * @var string 
     */
	protected static $_objectTitle = 'HTML Content with Image'; 
	
    /**
     * For editable div in Image editor 
     * REMOVED BECAUSE IT CONFLICTS WITH THE EDITOR
     * 
     * @var string
     */
//	protected static $_editableTitle = "Open HTML editor";  

    /**
     * The View Parameter From Image Editor
     *
     * @var string
     */
	protected $_viewParameter;
	
    /**
     * Differentiates each of this instance
     *
     * @var int
     */
	protected static $_counter = 0;
	
    /**
	 * Returns text for the "interior" of the Image Editor
	 * The default is to display view and option parameters.
	 * 		
     * @param array Object Info
     * @return string HTML
     */
    public static function getHTMLForLayoutEditor( $object )
	{
		$object['editable'] = @$object['editable'] ? : 
       ( "
<style>
.ac-container input:checked + label, .ac-container input:checked + label:hover {
    background: #333;
    color: #EEE;
    text-shadow: 0px 1px 1px rgba(255,255,255, 0.6);
    box-shadow: 0px 0px 0px 1px rgba(155,155,155,0.3), 0px 2px 2px rgba(0,0,0,0.1);
}

.main {
       margin: 10% auto 1%;
       width: 30%;
   background-color: #587498;
    padding: 3em 3em 3em 3em;
}
.ac-container{
	text-align: left;
}
.ac-container label{
	    font-family: 'Raleway', sans-serif;
	padding: 5px 20px;
	position: relative;
	z-index: 20;
	display: block;
	cursor: pointer;
	color: #777;
	text-shadow: 1px 1px 1px rgba(255,255,255,0.8);
	line-height: 33px;
	font-size: 19px;
	background: #ffffff;
	background: -moz-linear-gradient(top, #ffffff 1%, #eaeaea 100%);
	background: -webkit-gradient(linear, left top, left bottom, color-stop(1%,#ffffff), color-stop(100%,#eaeaea));
	background: -webkit-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
	background: -o-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
	background: -ms-linear-gradient(top, #ffffff 1%,#eaeaea 100%);
	background: linear-gradient(top, #ffffff 1%,#eaeaea 100%);
	filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#ffffff', endColorstr='#eaeaea',GradientType=0 );
	box-shadow: 
		0px 0px 0px 1px rgba(155,155,155,0.3), 
		1px 0px 0px 0px rgba(255,255,255,0.9) inset, 
		0px 2px 2px rgba(0,0,0,0.1);
}
.ac-container label:hover{
	background: #fff;
}
.ac-container input:checked + label,
.ac-container input:checked + label:hover{
    text-shadow: 0px 1px 1px rgba(255,255,255, 0.6);
    box-shadow: 0px 0px 0px 1px rgba(155,155,155,0.3), 
 0px 2px 2px rgba(0,0,0,0.1);
}
.ac-container label:hover:after,
.ac-container input:checked + label:hover:after{
	content: '';
	position: absolute;
	width: 24px;
	height: 24px;
	right: 13px;
	top: 7px;
	background: transparent url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAJCAYAAAACTR1pAAAArElEQVQokY3QMQrCUAwG4EyFHkMQhILgZcLLFZz0mQyO9QJdSptsnkEQBKG3EQpeog5qbftsNZDt/0ISAABwO12j5DOYKMQ0clwe0GcxAAA41j2JNY71hr6YjyFiPZFYQ6wV+iwGYq1IrGnxplyEyM5tRvSObAmgz+IxjJhGJHbpIZ8vP1OHWLTGbbEKEFsS3vHE13ew2060/oqm8E/Uw68V/0Zd7FiPww936wFG3Y5YUkftaQAAAABJRU5ErkJggg==') no-repeat center center;	
}
.ac-container input:checked + label:hover:after{
	background-image: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAJCAYAAAACTR1pAAAAdElEQVQokY3QywmDUBAF0LcS7CMgCEIKSwHZphKbCAQEwWICgTRxssh7oDJ+7vbOGYZJKQhq9GiiPkxGL/980J5Fg2X28QbaxxmNq8Hr7GT4LnCA3uUpqALcFRii2eIItwn3LbTCzzw3oS7FDZeDj1d4FPQDT+ADdbpuj2QAAAAASUVORK5CYII=');
}
.ac-container input{
	display: none;
}
.ac-container article{
	background: rgba(255, 255, 255, 0.5);
	margin-top: -1px;
	overflow: hidden;
	height: 0px;
	position: relative;
	z-index: 10;
	-webkit-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
	-moz-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
	-o-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
	-ms-transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
	transition: height 0.3s ease-in-out, box-shadow 0.6s linear;
}
.ac-container article p{
	    line-height: 23px;
    font-size: 14px;
    padding: 15px 20px;
    font-family: 'Raleway', sans-serif;
    text-shadow: 1px 1px 1px rgba(255,255,255,0.8);
    background-color: #fff;
}
.ac-container input:checked ~ article{
	-webkit-transition: height 0.5s ease-in-out, box-shadow 0.1s linear;
	-moz-transition: height 0.5s ease-in-out, box-shadow 0.1s linear;
	-o-transition: height 0.5s ease-in-out, box-shadow 0.1s linear;
	-ms-transition: height 0.5s ease-in-out, box-shadow 0.1s linear;
	transition: height 0.5s ease-in-out, box-shadow 0.1s linear;
	box-shadow: 0px 0px 0px 1px rgba(155,155,155,0.3);
}
.ac-container input:checked ~ article.ac-small{
	height: 140px;
}
.ac-container input:checked ~ article.ac-medium{
	height: 180px;
}
.ac-container input:checked ~ article.ac-large{
	height: 230px;
}
.login-form p {
    font-size: 0.9em;
    text-align: center;
    line-height: 1.9em;
    color: #fff;
    margin-top: 1em;
}

/*==================================================
 * Effect 2
 * ===============================================*/
.effect2{
  position: relative;
}
.effect2:before, .effect2:after{
	     z-index: -999;
    position: absolute;
    content: '';
    bottom: 11px;
    left: 15px;
    width: 57%;
    top: 84%;
    max-width: 348px;
    background: rgba(255, 255, 255, 0.85);
    -webkit-box-shadow: 0 15px 10px #777;
    -moz-box-shadow: 0 15px 10px #777;
    box-shadow: 0 15px 10px rgba(179, 175, 175, 0.94);
    -webkit-transform: rotate(-3deg);
    -moz-transform: rotate(-3deg);
    -o-transform: rotate(-3deg);
    -ms-transform: rotate(-3deg);
    transform: rotate(-3deg);
}
.effect2:after{
  -webkit-transform: rotate(3deg);
  -moz-transform: rotate(3deg);
  -o-transform: rotate(3deg);
  -ms-transform: rotate(3deg);
  transform: rotate(3deg);
  right: 15px;
  left: auto;
}

/*--copy-right--*/
.copy-right {
       margin: 11em 0px 2em 0;
}
.copy-right p {
color: #444;
    font-size: 1em;
    font-weight: 400;
    margin: 0 auto;
    text-align: center
}
.copy-right p a {
	  color: #e86850;
}
.copy-right p a:hover {
	text-decoration: underline;
}
/*--//copy-right--*/
/*--start-responsive-design--*/
@media (max-width:1600px){


}
@media (max-width:1440px){

}
@media (max-width:1366px){

}
@media (max-width:1280px){
	.main {
		margin: 10% auto 1%;
		width: 33%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:1024px){
	.main {
		margin: 10% auto 1%;
		width: 41%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:991px){
	.main {
		margin: 10% auto 1%;
		width: 54%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:800px){
	.main {
		margin: 10% auto 1%;
		width: 54%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:768px){
	.main {
		margin: 10% auto 1%;
		width: 54%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:736px){
	.main {
		margin: 10% auto 1%;
		width: 65%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:667px){
	.main {
		margin: 10% auto 1%;
		width: 65%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:640px){
	.main {
		margin: 10% auto 1%;
		width: 65%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:568px){
	.ac-container {
		width: 310px;
		margin: 10px auto 30px auto;
		text-align: left;
	}
	body h1 {
		padding: 0em 0 1em 0;
		font-size: 1.5em;
	}
	.main {
		margin: 40% auto 1%;
		width: 65%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:480px){
	.ac-container {
		width: 310px;
		margin: 10px auto 30px auto;
		text-align: left;
	}
	body h1 {
		padding: 0em 0 1em 0;
		font-size: 1.5em;
	}
	.main {
		margin: 40% auto 1%;
		width: 65%;
		background-color: #587498;
		padding: 3em 3em 3em 3em;
	}
}
@media (max-width:414px){
	.main {
		margin: 40% auto 1%;
		width: 73%;
		background-color: #587498;
		padding: 2em;
	}
	.ac-container {
		width: 264px;
		margin: 10px auto 30px auto;
		text-align: left;
	}
	.ac-container article p {
    line-height: 21px;
    font-size: 13px;
    padding: 9px 20px;
	}
	.copy-right p {
		font-size: 0.9em;
		line-height: 1.9em;
	}
}
@media (max-width:384px){
	.main {
		margin: 40% auto 1%;
		width: 73%;
		background-color: #587498;
		padding: 2em;
	}
	.ac-container article p {
    line-height: 21px;
    font-size: 13px;
    padding: 9px 20px;
	}
	.main {
		margin: 31% auto 1%;
		width: 73%;
		background-color: #587498;
		padding: 2em;
	}
}
@media (max-width:375px){
	.main {
		margin: 40% auto 1%;
		width: 73%;
		background-color: #587498;
		padding: 2em;
	}
}
@media (max-width:320px){
  .ac-container article p {
    line-height: 21px;
    font-size: 12px;
    padding: 9px 20px;
	}
	.ac-container {
		width: 221px;
		margin: 10px auto 20px auto;
		text-align: left;
	}
	body h1 {
		padding: 0em 0 1em 0;
		font-size: 1.3em;
	}
	.main {
		margin: 21% auto 1%;
		width: 73%;
		background-color: #587498;
		padding: 2em;
	}
}
</style>"

        .

        '		<section class="ac-container">
				<div>
					<input id="ac-1" name="accordion-1" type="checkbox">
					<label for="ac-1">Section 1</label>
					<article class="ac-small">
						<p>Well, the way they make shows is, they make one show. </p>
					</article>
				</div>
				<div>
					<input id="ac-2" name="accordion-1" type="checkbox">
					<label for="ac-2">Section 2</label>
					<article class="ac-medium">
						<p>Like you, I used to think the world was this great place where everybody lived by the same standards I did, then some kid with a nail showed me I was living in his world, a world where chaos rules not order, a world where righteousness is not rewarded.  </p>
					</article>
				</div>
				<div>
					<input id="ac-3" name="accordion-1" type="checkbox">
					<label for="ac-3">Section 3</label>
					<article class="ac-large">
						<p>You think water moves fast? You should see ice. It moves like it has a mind. Like it knows it killed the world once and got a taste for murder. After the avalanche, it took us a week to climb out.  </p>
					</article>
				</div>
				<div>
					<input id="ac-4" name="accordion-1" type="checkbox">
					<label for="ac-4">Section 4</label>
					<article class="ac-large">
						<p>Ted did figure it out - time travel. And when we get back, we gonna tell everyone. that means we never told anyone. And if we never told anyone it means we never made it back. Hence we die down here. Just as a matter of deductive logic. </p>
					</article>
				</div>
			</section>
')
        ;
		return parent::getHTMLForLayoutEditor( $object );
	}
 
	// END OF CLASS
}
