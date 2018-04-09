{config_load file=ckeditor_lang_conf}
{if $Story_Content_Tags_To_Allow neq ''}
	{php}
		global $main_smarty;
		$allowed_tags = $main_smarty->get_template_vars('Story_Content_Tags_To_Allow');
		$removebuttons = 'NewPage,Templates,PasteFromWord,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Flash,Table,PageBreak,Iframe,Anchor,Save,Link,Unlink,Font,CreateDiv,Image,Smiley,JustifyLeft,JustifyCenter,JustifyRight,JustifyBlock,BidiLtr,BidiRtl,TextColor,BGColor,FontSize';
		//$removebuttons = 'NewPage,Templates,PasteFromWord,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Flash,Table,PageBreak,Iframe,Anchor,Save,Link,Unlink,Font,CreateDiv,Image';

		$config_var = 'config.removeButtons = ';
		$pattern = "/$config_var/i";
		$in_config = preg_grep($pattern, file('./modules/ckeditor/ckeditor/config.js'));
		$in_config = str_replace($config_var, '', $in_config);
		foreach($in_config as $in_conf) {
			$current_config = $in_conf;
			$current_config = str_replace("'", '', $current_config);
			$current_config = str_replace(";", '', $current_config);
		}
	
		$ckeditor_tags = array('Bold','Italic','Underline','Strike','Subscript','Superscript','NumberedList','BulletedList','Blockquote','HorizontalRule');
		$syn = array();
		$allowed_tags = str_replace('&lt;', '', $allowed_tags);
		$allowed_tags = str_replace('&gt;', ',', $allowed_tags);
		$allowed_tags = explode(',', $allowed_tags);
		foreach($allowed_tags as $tag) {
			if ($tag == 'strong') {
				$syn[] = 'Bold';
			}elseif ($tag == 'em') {
				$syn[] = 'Italic';
			}elseif ($tag == 'u') {
				$syn[] = 'Underline';
			}elseif ($tag == 's') {
				$syn[] = 'Strike';
			}elseif ($tag == 'sub') {
				$syn[] = 'Subscript';
			}elseif ($tag == 'sup') {
				$syn[] = 'Superscript';
			}elseif ($tag == 'ol') {
				$syn[] = 'NumberedList';
			}elseif ($tag == 'ul') {
				$syn[] = 'BulletedList';
			}elseif ($tag == 'blockquote') {
				$syn[] = 'Blockquote';
			}elseif ($tag == 'hr') {
				$syn[] = 'HorizontalRule';
			}
		}
		
		$result_difference=array_diff($ckeditor_tags,$syn);
		if (sizeof($result_difference) > 0) {
			$removeButtons_append = '';
			foreach($result_difference as $diff) {
				$removeButtons_append .= ','.$diff;
			}
			$replacement = "config.removeButtons = '".$removebuttons . $removeButtons_append."'";
			$filename = "./modules/ckeditor/ckeditor/config.js";
			$filedata = file_get_contents($filename);
			$filedata = preg_replace('/config.removeButtons = [\'\"](.*)[\'\"]/iu',$replacement,$filedata);

			// Write the changes to the config.js files
			$lang_file = fopen($filename, "w");
			fwrite($lang_file, $filedata);
			fclose($lang_file);
		}else{
			if (trim($current_config) != trim($removebuttons)) {
				$replacement = $config_var . "'". $removebuttons. "'";
				$filename = "./modules/ckeditor/ckeditor/config.js";
				$filedata = file_get_contents($filename);
				$filedata = preg_replace('/config.removeButtons = [\'\"](.*)[\'\"]/iu',$replacement,$filedata);

				// Write the changes to the config.js files
				$lang_file = fopen($filename, "w");
				fwrite($lang_file, $filedata);
				fclose($lang_file);
			}
		}
	{/php}
	{literal}<script>CKEDITOR.replace( 'bodytext', {

			// List of text formats available for this editor instance.
			format_tags: 'pre;address;div'
		} );</script>{/literal}
	{*if $SubmitSummary_Allow_Edit neq ''*}
		{*literal*}<!--<script>CKEDITOR.replace( 'summarytext' );</script>-->{*/literal*}
	{*/if*}
{/if}
{config_load file=ckeditor_plikli_lang_conf}