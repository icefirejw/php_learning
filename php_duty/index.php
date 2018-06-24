<?php
include_once 'include/init.php';

$header = get_header("duty schedule");
$tailer = get_tailer();

$thisyear = date('Y');
$thismonth = date('m');

$print_year = print_year_month($thisyear);
echo <<< EOT
	{$header}
	{$print_year}
	{$tailer}
EOT;
?>