<?php
include_once 'include/init.php';

$header = get_header("month");
$tailer = get_tailer();

$thisyear = date('Y');

$adminidx = print_admin_idx();
$print_year = print_year_month($thisyear);


echo <<< EOT
	{$header}
	{$adminidx}<br><br>
	{$print_year}
	{$tailer}
EOT;

?>