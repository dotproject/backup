<?php
// backup database module for dotProject
// (c)2003 Daniel Vijge
// This program is free software; you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation; either version 2 of the License, or
// (at your option) any later version.

// get the correct path to do_backup.php
$result = mysql_query('SELECT mod_directory FROM modules WHERE mod_name=\'backup\'');
$row = mysql_fetch_assoc($result);
$backup_path = './modules/'.$row['mod_directory'].'/';
?>
<script>
	function check_backup_options()
	{
		var f = document.frmBackup;
		if(f.export_what.options[f.export_what.selectedIndex].value==3)
		{
			f.droptable.enabled=false;
			f.droptable.checked=false;
		}
		else
		{
			f.droptable.enabled=true;
		}
	}
</script>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
<td valign="top" align="left" width="98%">

<table width="100%" border="0" cellpadding="1" cellspacing="1">
<tr>
<td width="36"><img src="./images/icons/companies.gif" height="36" alt="" border="0" /></td>
<td align="left" width="100%" nowrap="nowrap"><h1>Backup database</h1></td>
</tr>
</table>

<table cellspacing="0" cellpadding="4" border="0" width="100%" class="std">
	<form onclick="check_backup_options()" name="frmBackup" action="<?php echo $backup_path ?>do_backup.php" method="post">
	<tr>
		<td align="right" valign="top" nowrap="nowrap">
			Export
		</td>
		<td width="100%" nowrap="nowrap">
			<select name="export_what" style="font-size:10px" >
				<option value="1" checked="checked" />Table structure and data
				<option value="2" />Only table strucure
				<option value="3" />Only data
			</select>
		</td>
	</tr>
	<tr>
		<td align="right" valign="top"  nowrap="nowrap">
			Extra options
		</td>
		<td width="100%" nowrap="nowrap">
			<input type="checkbox" name="droptable" checked="checked" />Add 'DROP TABLE' to output-script<br />
		</td>
	</tr>
	<tr>
		<td align="right" valign="top"  nowrap="nowrap">
			Save as
		</td>
		<td width="100%" nowrap="nowrap">
			<select name="compress" style="font-size:10px" >
				<option value="1" checked="checked" />Compressed .ZIP file
				<option value="0" />Plain text file
			</select>
		</td>
	</tr>
	<tr>
		<td>
			&nbsp;
		</td>
		<td align="right">
			<input type="submit" value="Download backup" class="button"/>
		</td>
	</tr>
	</form>
</table>