<?php


defined('_JEXEC') or die();

JHtml::_('behavior.tooltip');
?>
<form action="<?php echo JRoute::_('index.php?option=com_sportsmanagement'); ?>" method="post" name="adminForm" id="adminForm">
	<table class="adminlist">
		<thead>
			<tr>
				<th width="5"><?php echo JText::_( 'COM_SPORTSMANAGEMENT_JSMGCALENDAR_FIELD_NAME_ID_LABEL' ); ?></th>
				<th width="20"><input type="checkbox" name="toggle" value=""
					onclick="checkAll(<?php echo count( $this->items ); ?>);" /></th>
				<th><?php echo JText::_( 'COM_SPORTSMANAGEMENT_JSMGCALENDAR_FIELD_NAME_LABEL' ); ?></th>
				<th><?php echo JText::_( 'COM_SPORTSMANAGEMENT_JSMGCALENDAR_FIELD_COLOR_LABEL' ); ?></th>
				<th><?php echo JText::_( 'COM_SPORTSMANAGEMENT_JSMGCALENDAR_FIELD_CALENDAR_ID_LABEL' ); ?></th>
				<th><?php echo JText::_( 'COM_SPORTSMANAGEMENT_JSMGCALENDAR_VIEW_GCALENDARS_COLUMN_AUTHENTICATION' ); ?></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($this->items as $i => $item){?>
			<tr class="row<?php echo $i % 2; ?>">
				<td><?php echo $item->id; ?></td>
				<td>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				</td>
				<td>
					<a href="<?php echo JRoute::_( 'index.php?option=com_sportsmanagement&task=jsmgcalendar.edit&id='. $item->id ); ?>">
						<?php echo $item->name; ?>
					</a>
				</td>
				<td width="40px"><div style="background-color: <?php echo jsmGCalendarUtil::getFadedColor($item->color);?>;width: 40px;height: 40px;"></div></td>
				<td style="border: 0;"><?php echo urldecode($item->calendar_id); ?></td>
				<td style="border: 0;"><?php
				if(!empty($item->magic_cookie)){
					echo JText::_('COM_SPORTSMANAGEMENT_JSMGCALENDAR_FIELD_MAGIC_COOKIE_LABEL');
				} else if(!empty($item->username)){
					echo JText::_('COM_SPORTSMANAGEMENT_JSMGCALENDAR_VIEW_GCALENDARS_COLUMN_AUTHENTICATION_USERNAME');
				} else {
					echo JText::_('COM_SPORTSMANAGEMENT_JSMGCALENDAR_VIEW_GCALENDARS_COLUMN_AUTHENTICATION_NO');
				}
				?></td>
			</tr>
		<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="6">
					<?php echo $this->pagination->getListFooter(); ?>
					<br/><br/>
					
				</td>
			</tr>
		</tfoot>
	</table>
	<div>
		<input type="hidden" name="view" value="jsmgcalendars" />
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
