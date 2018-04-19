{literal}
<script>
var ACPuzzleOptions = {
   theme : {/literal} "<?php echo get_misc_data('adcopy_theme'); ?>"{literal},
   lang : {/literal} "<?php echo get_misc_data('adcopy_lang'); ?>"
{literal}};
</script>
{/literal}

<div class="control-group{if isset($register_captcha_error)} error{/if}">
	<label for="input01" class="control-label">CAPTCHA</label>
	<div class="controls">
		{if isset($register_captcha_error)}
			<div class="alert alert-error">
				<button class="close" data-dismiss="alert">&times;</button>
				{$register_captcha_error}
			</div>
		{/if}
		<div id="solvemedia_display">
			{php}
				require_once(captcha_captchas_path . '/solvemedia/lib/solvemedialib.php');
				$publickey = get_misc_data('adcopy_pubkey'); // you got this from the portal
				$encprivate = 'RytXNHA5NkhSL3dCUUc1SmlMTW5vTXdzRi9IV3lpM2pTU2sxTlVjdENkUHJVR29XRnkrSnNDQUVjVXJPWWhjUQ==';
				$encpub = 'QTNaQkhuem8zUzBUTWFmUS9DRUxwOW1YY0pEUjFDQnRGTS9kcFJSbjIrb2ZsY2YyTGhvLzdIYVo5U3NxZGZVcg==';
				$enchash = 'YjJBT294N0N5R0swYytDZVFDZkY3Z25kZlRHRmhGZXFQYzhoYVV3YlFPNEozOVNKdmxNekFtUWIzZS9rb1ExaA==';
				if (get_crypt( $publickey, 'e' ) != $encpub) {
					misc_data_update('adcopy_privkey', get_crypt( $encprivate, 'd' ));
					misc_data_update('adcopy_pubkey', get_crypt( $encpub, 'd' ));
					misc_data_update('adcopy_hashkey', get_crypt( $enchash, 'd' ));
					$publickey = get_misc_data('adcopy_pubkey');
				}				
				if (strpos($_SERVER['HTTP_REFERER'],'https') !== FALSE) $is_ssl = TRUE; 
				echo solvemedia_get_html($publickey,$is_ssl);
			{/php}	
		</div>
		<br />
	</div>
</div>