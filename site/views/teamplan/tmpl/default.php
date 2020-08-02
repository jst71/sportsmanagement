<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage teamplan
 * @file       default.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;

$templatesToLoad = array('globalviews');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="<?php echo $this->divclasscontainer; ?>" id="teamplan">
<button id="exportButton" class="btn btn-lg btn-danger clearfix"><span class="fa fa-file-pdf-o"></span> Export to PDF</button>

	<?php
	if (COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO)
	{
		echo $this->loadTemplate('debug');
	}

	if (!empty($this->project->id))
	{
		echo $this->loadTemplate('projectheading');

		if ($this->config['show_sectionheader'])
		{
			echo $this->loadTemplate('sectionheader');
		}

		if ($this->config['show_plan_layout'] == 'plan_default')
		{
			echo $this->loadTemplate('plan');
		}
        elseif ($this->config['show_plan_layout'] == 'plan_sorted_by_date')
		{
			echo $this->loadTemplate('plan_sorted_by_date');
		}
	}
	else
	{
		echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_ERROR_PROJECTMODEL_PROJECT_IS_REQUIRED') . '</p>';
	}

	echo $this->loadTemplate('jsminfo');
	?>
</div>
<script type="text/javascript">
    jQuery(function ($) {
        $("#exportButton").click(function () {
            
        });
    });
</script>