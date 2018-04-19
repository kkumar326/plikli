<?php

############################################################################################
## This file must be copied to the ROOT installation to run it manually or in a Cron Job. ##
## If you run it from the rss_import root, you will get fatal errors!*/                   ##
############################################################################################

include_once('internal/Smarty.class.php');
$main_smarty = new Smarty;

include_once('config.php');	

include_once(mnminclude.'html1.php');
include_once(mnminclude.'link.php');
include_once(mnminclude.'tags.php');
include_once(mnminclude.'smartyvariables.php');

include_once(mnmmodules.'rss_import/rss_import_settings.php');
include_once(mnmmodules.'rss_import/rss_import_main.php');

rss_import_do_import(false);

?>