<?php
declare(strict_types=1);

/**
 * @package     ${package}
 * @subpackage  ${subpackage}
 *
 * @copyright   Copyright (C) ${build.year} ${copyrights}, All rights reserved.
 * @license     ${license.name}; see ${license.url}
 */

use Joomla\CMS\Form\Form;
use Joomla\CMS\Plugin\CMSPlugin;

defined('_JEXEC') or die;

/**
 * BP Captcha Plugin
 * Simple captcha plugin that avoids user interactions.
 *
 * @since  1.0
 */
class PlgCaptchaBPCaptcha extends CMSPlugin
{
	/**
	 * Load the language file on instantiation.
	 *
	 * @var    boolean
	 * @since  1.0
	 */
	protected $autoloadLanguage = true;

	/**
	 * Plugin layouts path.
	 *
	 * @since 1.0
	 */
	const LAYOUT_PATH = JPATH_PLUGINS . '/captcha/bpcaptcha/layouts';

	/**
	 * Plugin layout name.
	 *
	 * @since 1.0
	 */
	const LAYOUT_NAME = 'plg_captcha_bpcaptcha.field';

	/**
	 * Session variable holding field names.
	 *
	 * @since 1.0
	 */
	const SESSION_VARIABLE = 'plg_captcha_bpcaptcha.fields';

	/**
	 * Display captcha.
	 *
	 * @param   string  $name   Name of the field.
	 * @param   string  $id     Field ID.
	 * @param   string  $class  Field class.
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function onDisplay($name = 'bpcapcha_1', $id = 'bpcapcha_1', $class = '')
	{
		$this->storeCaptchaFieldName($name);

		$xml  = new \SimpleXMLElement('<form><field name="' . $name . '" type="text" /></form>');
		$form = new Form($name);
		$form->load($xml);

		// Input layout
		$layout = new Joomla\CMS\Layout\FileLayout(static::LAYOUT_NAME, static::LAYOUT_PATH);

		return $layout->render(['field' => $form->getInput($name), 'name' => $name, 'id' => $id]);
	}

	/**
	 * Store informations about captcha field.
	 *
	 * @param   string  $name  Name of the field to store.
	 *
	 * @since 1.0.0
	 */
	protected function storeCaptchaFieldName(string $name)
	{
		$session  = JFactory::getSession();
		$fields   = $this->getCaptchaFieldNames();
		$fields[] = $name;
		$session->set(static::SESSION_VARIABLE, $fields);
	}

	/**
	 * Get list of captcha fields.
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	protected function getCaptchaFieldNames(): array
	{
		$session = JFactory::getSession();

		return $session->get(static::SESSION_VARIABLE, []);
	}

	/**
	 * Check captcha.
	 *
	 * @param $code Input provided by user (not used in this plugin).
	 *
	 * @return bool
	 *
	 * @throws Exception
	 * @since 1.0.0
	 */
	public function onCheckAnswer($code = '')
	{

		// App input
		$input = JFactory::getApplication()->input;

		// List of captcha fields
		$fields = $this->getCaptchaFieldNames();

		// Check if any of those is filled
		$result = empty($code);
		foreach ($fields as $name)
		{
			if (!empty($input->get($name)))
			{
				$result = false;

				continue;
			}
		}

		// Fields,
		$this->clearCaptchaFieldNames();

		// Discard spam submissions
		if (!$result)
		{
			$this->_subject->setError(JText::_('PLG_CAPTCHA_BPCAPTCHA_ERROR'));
		}

		return $result;
	}

	/**
	 * Clear captcha field names list.
	 *
	 * @since 1.0.0
	 */
	protected function clearCaptchaFieldNames()
	{
		$session = JFactory::getSession();
		$session->clear(static::SESSION_VARIABLE);
	}

}
