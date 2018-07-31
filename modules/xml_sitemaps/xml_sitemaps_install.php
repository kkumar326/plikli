<?php
/*
    XML Sitemaps module for Plikli
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
	$module_info['homepage_url'] = 'https://www.plikli.com/mods/xml_sitemaps.zip';
	$module_info['settings_url'] = 'admin_config.php?page=XmlSitemaps';	
	
	if(!defined('XmlSitemaps_friendly_url')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_friendly_url','false','false','true / false','Sitemap Friendly URLs','This makes friendly sitemap urls. SET to TRUE, only if you have set URL method to 2 in Dashboard -> Settings -> SEO -> URL Method.', 'define')";
	}
	if(!defined('XmlSitemaps_Links_per_sitemap')){
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','XmlSitemaps_Links_per_sitemap','500','500','positive numerical value','Links per Sitemap','This module generates an index of sitemaps, here you can set the number of links you want to include in one sitemap from that index. <STRONG>NOTE THAT GOOGLE RECOMMENDS A MAXIMUM OF 1000 PER SITEMAP</STRONG>','define')";
	}

/*** Redwine: Added a Sitemap Links Page to the module's settings to allow a visual and quick access to the generated files ***/
	$module_info['db_sql'][]="insert into ".table_prefix."config (var_page,var_name,var_value,var_defaultvalue,var_optiontext,var_title,var_desc,var_method) values ('XmlSitemaps','','','','link','Sitemap links page','<a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\" rel=\"noopener noreferrer\">View the Sitemapindex</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Links Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=pages0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Pages Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=users0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Users Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=groups0\" target=\"_blank\" rel=\"noopener noreferrer\">View the Groups Sitemap</a><br /><a href\=\"../module.php?module=xml_sitemaps_show_sitemap&i=main\" target=\"_blank\" rel=\"noopener noreferrer\">View the Navigations Sitemap</a>','define')";
?>