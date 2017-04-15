<?php
/*
    XML Sitemaps module for Kliqqi
    Copyright (C) 2007-2008  Secasiu Mihai - http://patchlog.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
	$module_info['name'] = 'Xml sitemaps';
	$module_info['desc'] = "This module creates xml sitemaps to be used by all search engines.";
	$module_info['version'] = 3.0;
	$module_info['update_url'] = '';
	$module_info['homepage_url'] = 'http://www.kliqqi.com/mods/xml_sitemaps.zip';
	$module_info['settings_url'] = 'admin_config.php?page=XmlSitemaps';	
	
	/*if(!defined('XmlSitemaps_ping_google')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_ping_google','false','false','true / false','Ping Google?','Ping Google when new story posted', 'define')";
	}
	if(!defined('XmlSitemaps_ping_ask')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_ping_ask','false','false','true / false','Ping Ask.com?','Ping Ask.com when new story posted', 'define')";
	}

	if(!defined('XmlSitemaps_ping_yahoo')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_ping_yahoo','false','false','true / false','Ping Yahoo?','Ping Yahoo! when new story posted', 'define')";
	}
	
	if(!defined('XmlSitemaps_yahoo_key')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_yahoo_key','','','alfanumaric key from yahoo.com','Yahoo siteexplorer key','This key is required if you want to ping yahoo,<br />Don\\'t have one? get it <a href=\"http://developer.yahoo.com/wsregapp/index.php\">here</a>', 'define')";
	}*/

	if(!defined('XmlSitemaps_friendly_url')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_friendly_url','false','false','true / false','Sitemap Friendly URLs','This makes friendly sitemap urls. SET to TRUE, only if you have set URL method to 2 in Dashboard -> Settings -> SEO -> URL Method.', 'define')";
	}
	if(!defined('XmlSitemaps_Links_per_sitemap')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_Links_per_sitemap','500','500','positive numerical value','Links per Sitemap','This module generates an index of sitemaps, here you can set the number of links you want to include in one sitemap from that index. <STRONG>NOTE THAT GOOGLE RECOMMENDS A MAXIMUM OF 1000 PER SITEMAP</STRONG>','define')";
	}
	/*if(!defined('XmlSitemaps_use_cache')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_use_cache','false','false','true / false','Use cache','Serve the sitemap from the disk cache, and don\\'t ping any services untill the cache expires','define')";
	}

	if(!defined('XmlSitemaps_cache_ttl')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_cache_ttl','3600','3600','positive numerical value','Cache TTL','Cache life time in seconds','define')";
	}*/
/*** Redwine: Added a Sitemap Links Page to the module's settings to allow a visual and quick access to the generated files ***/
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','','','','link','Sitemap links page','<a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\">View the Sitemapindex</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=0\" target=\"_blank\">View the Links Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=pages0\" target=\"_blank\">View the Pages Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=users0\" target=\"_blank\">View the Users Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=groups0\" target=\"_blank\">View the Groups Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\">View the Navigations Sitemap</a>','define')";
?>
