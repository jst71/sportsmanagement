<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage globalviews
 * @file       deafault_extrafields.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Factory;

$this->notes = array();
$this->notes[] = Text::_('COM_SPORTSMANAGEMENT_EXTRA_FIELDS');
echo $this->loadTemplate('jsm_notes');

$this->tips = array();

if (isset($this->extrafields))
{
    $ausgabe = '<table class="table">';
	foreach ($this->extrafields as $field)
	{
		$value      = $field->fvalue;
		$field_type = $field->field_type;


		if (!empty($value)) // && !$field->backendonly)
		{
          switch (Factory::getApplication()->input->getVar('view'))
					{
						case 'clubinfo':
							$title = $this->club->name;
							break;
					}
          
$ausgabe .= '<tr>';          
$ausgabe .= '<td>'.Text::_($field->name).'</td>';
switch ($field_type)
					{
						case 'link':
							$ausgabe .= '<td>'. HTMLHelper::_('link', $field->fvalue, $title, array("target" => "_blank")).'</td>';
							break;
						default:
							$ausgabe .= '<td>'. Text::_($field->fvalue).'</td>';
							break;
					}          
        
$ausgabe .= '</tr>';          
         
		}
	}
$ausgabe .= '</table>';
$this->tips[] = $ausgabe;    
}


echo $this->loadTemplate('jsm_tips');