<% control ManyMonthsCalendar %>
	<table id="$TableID" cellpadding="0" cellspacing="$CellSpacing" class="{$TableClass}-Table" summary="Calendar for $MonthName $YearName">
		<thead>
			<tr>
				<% if EnableNavigation %>
					<td class="{$TableClass}-PreviousLink"><a href="{$PageLink}?m=$PreviousMonthMonthNumber&amp;y=$PreviousMonthYearNumber">$PreviousMonthNavText</a></td>
				<% end_if %>
				<td colspan="$Colspan" class="{$TableClass}-Title">$MonthName $YearName</td>
				<% if EnableNavigation %>
					<td class="{$TableClass}-NextLink"><a href="{$PageLink}?m=$NextMonthMonthNumber&amp;y=$NextMonthYearNumber">$NextMonthNavText</a></td>
				<% end_if %>
			</tr>
			<tr class="{$TableClass}-Days">
				<% control DayNames %>
						<th scope="col">$DayName</th>
				<% end_control %>
			</tr>
		</thead>
		<tbody>
			<tr>
		<% control Days %>
				<td class="{$TableClass}-Cell <% if EvenCol %>{$TableClass}EvenCol<% end_if %> <% if OutsideCurrentMonth %>{$TableClass}-Outside<% end_if %> <% if IsCurrentDay %>{$TableClass}-CurrentDay<% end_if %>">
					<span>$Day</span>
				<% if Events %>
					<% control Events %>
						<% if Link %>
							<a href="$Link" class="{$TableClass}-$FirstLast {$TableClass}-Event">$Title</a>
						<% else %>
							$Title
						<% end_if %>
					<% end_control %>
				<% end_if %>
				</td>
				<% if LastDayOfTheWeek %>
			</tr><tr <% if EvenRow %>class="{$TableClass}EvenRow"<% end_if %>>
				<% end_if %>
		<% end_control %>
			</tr>
		</tbody>
	</table>
<% end_control %>