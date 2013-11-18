<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Captcha.SimpleCaptcha
 *
 * @copyright   Copyright (C) 2013 Artur Stępień, Inc. All rights reserved.
 * @license     GNU General Public License version 3 or later; see LICENSE
 */
defined('_JEXEC') or die;


class PlgCaptchaSimplecaptcha extends JPlugin {

	protected $autoloadLanguage = true;
	protected $number1;
	protected $number2;
	protected $numbers = array(
				   0 => 'zero',
				   1 => 'jeden',
				   2 => 'dwa',
				   3 => 'trzy',
				   4 => 'cztery',
				   5 => 'pięć',
				   6 => 'sześć',
				   7 => 'siedem',
				   8 => 'osiem',
				   9 => 'dziewięć',
				);
	
	public function onInit($id) {		
		$this->number1 = rand(0,9);
		$this->number2 = rand(0,9);
		$answer = (int)$this->number1 + (int)$this->number2;
		
		$session = JFactory::getSession();
		$session->set('simplecaptcha_answer', $answer);
		
		return true;
	}

	public function onDisplay($name, $id, $class) {
		$session = JFactory::getSession();
		return $this->numbers[$this->number1].' dodać '.$this->numbers[$this->number2].' to <input type="text" name="simplecaptcha_answer" id="simplecaptcha_answer" maxlength="3" size="3" placeholder="..." />';
	}

	
	public function onCheckAnswer($code) {
		$input = JFactory::getApplication()->input;
		
		$response = $input->getString('simplecaptcha_answer');

		// Discard spam submissions
		if ($response == null || strlen($response) == 0) {
			$this->_subject->setError(JText::_('PLG_SIMPLECAPTCHA_ERROR_EMPTY_SOLUTION'));

			return false;
		}

		$session = JFactory::getSession();
		
		$answer = ($response==$session->get('simplecaptcha_answer')); 

		if ($answer) {
			return true;
		} else {
			// @todo use exceptions here
			$this->_subject->setError(JText::_('PLG_SIMPLECAPTCHA_ERROR'));

			return false;
		}
	}

}
