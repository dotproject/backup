<?php
// backup database module for dotProject
// (c)2003 Daniel Vijge
// Licensed under GNU/GPL v2 or later

// Based on the work of the phpMyAdmin
// (c)2001-2002 phpMyAdmin group [http://www.phpmyadmin.net]

// list all tables
include('../../includes/config.php');
mysql_connect($dPconfig['dbhost'],$dPconfig['dbuser'],$dPconfig['dbpass']);
mysql_select_db($dPconfig['dbname']);
$alltables = mysql_list_tables($dPconfig['dbname']);

$output  = '';
$output .= '# Backup of database \'' . $dPconfig['dbname'] . '\'' . "\r\n";
$output .= '# Generated on ' . date('j F Y, H:i:s') . "\r\n";
$output .= '# OS: ' . PHP_OS . "\r\n";
$output .= '# PHP version: ' . PHP_VERSION . "\r\n";
$output .= '# MySQL version: ' . mysql_get_server_info() . "\r\n";
$output .= "\r\n";
$output .= "\r\n";

// fetch all tables on by one
while ($row = mysql_fetch_row($alltables))
{
	// introtext for this table
	$output .= '# TABLE: ' . $row[0] . "\r\n";
	$output .= '# --------------------------' . "\r\n";
	$output .= '#' . "\r\n";
	$output .= "\r\n";
	
	if (isset($_POST["droptable"]) && $_POST['droptable'] == 'on') 
	{
		// drop table
		$output .= 'DROP TABLE IF EXISTS `' . $row[0] . '`;' . "\r\n";
		$output .= "\r\n";
	}
	
	if ($_POST['export_what'] == 1 || $_POST['export_what'] == 2) 
	{
	    // structure of the table
		$table = mysql_query('SHOW CREATE TABLE ' . $row[0]);
		$create = mysql_fetch_array($table);
		// replace UNIX enter by Windows Enter for readability in Windows
		$output .= str_replace("\n","\r\n",$create[1]).';';
		$output .= "\r\n";
		$output .= "\r\n";
	}
	
if ($_POST['export_what'] == 1 || $_POST['export_what'] == 3) 
	{
	    $fields = mysql_list_fields($dPconfig['dbname'], $row[0]);
		$columns = mysql_num_fields($fields);
	
		// all data from table
		$result = mysql_query('SELECT * FROM '.$row[0]);
		while($tablerow = mysql_fetch_array($result))
		{
			$output .= 'INSERT INTO `'.$row[0].'` (';
			for ($i = 0; $i < $columns; $i++)
			{
				$output .= '`'.mysql_field_name($fields,$i).'`,';
			}
			$output = substr($output,0,-1); // remove last comma
			$output .= ') VALUES (';
			for ($i = 0; $i < $columns; $i++)
			{
				// remove all enters from the field-string. MySql stamement must be on one line
				$value = str_replace("\r\n",'\n',$tablerow[$i]);
				// replace ' by \'
				$value = str_replace('\'',"\'",$value);
				$output .= '\''.$value.'\',';
			}
			$output = substr($output,0,-1); // remove last comma
			$output .= ');' . "\r\n";
		} // while
		$output .= "\r\n";
		$output .= "\r\n";
	}
}
	// write to file, so it can be downloaded
$file = 'backup.sql';
if ($_POST['compress'] == '1') 
{
    $file = 'backup.zip';
	$mime_type = 'application/x-zip';
	header('Content-Disposition: inline; filename="' . $file . '"');
	header('Content-Type: ' . $mime_type);
	include('zip.lib.php');
	$zip = new zipfile;
	$zip->addFile($output,'/'.'backup.sql');
	echo $zip->file();
}
else
{
	$file = 'backup.sql';
	$mime_type = 'text/sql';
	header('Content-Disposition: inline; filename="' . $file . '"');
	header('Content-Type: ' . $mime_type);
	echo $output;
}
?>