<?php
	$options = baidu_tongji_get_option();
	$ajax_nonce = wp_create_nonce('baidu-tongji');
?>
<div class="wrap">
    <form action="" method="post">
		<table class="form-table">
			<tbody>
				<tr class="login_url">
					<th><label for="login_url">login_url</label></th>
					<td><input type="text" id="login_url" value="<?php echo $options['login_url']; ?>" class="regular-text"></td>
				</tr>
                <tr class="api_url">
                    <th><label for="api_url">api_url</label></th>
                    <td><input type="text" id="api_url" value="<?php echo $options['api_url']; ?>" class="regular-text"></td>
                </tr>
                <tr class="username">
                    <th><label for="username">username</label></th>
                    <td><input type="text" id="username" value="<?php echo $options['username']; ?>" class="regular-text"></td>
                </tr>
                <tr class="password">
                    <th><label for="password">password</label></th>
                    <td>
						<input type="password" id="password" value="<?php echo $options['password']; ?>" class="regular-text">
						<button id="hide_password" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
							<span class="dashicons dashicons-visibility"></span>	
							<span class="text"><?php echo __('Show'); ?></span>
						</button>
					</td>
                </tr>
                <tr class="token">
                    <th><label for="token">token</label></th>
                    <td>
						<input type="password" id="token" value="<?php echo $options['token']; ?>" class="regular-text">
						<button id="hide_token" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
							<span class="dashicons dashicons-visibility"></span>	
							<span class="text"><?php echo __('Show'); ?></span>
						</button>
					</td>
                </tr>
                <tr class="uuid">
                    <th><label for="uuid">uuid</label></th>
					<td><input type="text" id="uuid" value="<?php echo $options['uuid']; ?>" class="regular-text"></td>
                </tr>
                <tr class="account_type">
                    <th><label for="account_type">account_type</label></th>
					<td><input type="text" id="account_type" value="<?php echo $options['account_type']; ?>" class="regular-text"></td>
                </tr>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<td>
						<p name="submit" id="save" class="button button-primary"><?php echo __('Save'); ?></p>
					</td>
					<td><p id="response"></p></td>
				<tr>
			</tbody>
		</table>
	</form>
</div>
<script type="text/javascript">
(function($){
    $(function(){
		$('#hide_password').toggle(
			function(){
				$('#password').attr('type', 'text');
				$(this).find('.dashicons').removeClass('dashicons-visibility').addClass('dashicons-hidden');
				$(this).find('.text').text('<?php echo __('Hide'); ?>');
			},
			function(){
				$('#password').attr('type', 'password');
				$(this).find('.dashicons').removeClass('dashicons-hidden').addClass('dashicons-visibility');
              	$(this).find('.text').text('<?php echo __('Show'); ?>');
			}
		);
		
		$('#hide_token').toggle(
			function(){
				$('#token').attr('type', 'text');
				$(this).find('.dashicons').removeClass('dashicons-visibility').addClass('dashicons-hidden');
				$(this).find('.text').text('<?php echo __('Hide'); ?>');
			},
			function(){
				$('#token').attr('type', 'password');
				$(this).find('.dashicons').removeClass('dashicons-hidden').addClass('dashicons-visibility');
				$(this).find('.text').text('<?php echo __('Show'); ?>');
			}
		);
		
		$('#save').click(function(){
			$.ajax({
				type    :'POST',
				url     : ajaxurl,
				data    : {
					action           	: 'save_options',
					security			: '<?php echo $ajax_nonce; ?>',
					login_url        	: $('#login_url').val(),
					api_url			    : $('#api_url').val(),
					uuid                : $('#uuid').val(),
					username         	: $('#username').val(),
					password         	: $('#password').val(),
					token              	: $('#token').val(),
					account_type    	: $('#account_type').val()
				},
				success: function(data) {
					$('#response').text(data);
					setTimeout(function(){$('#response').text('')}, 1500)
				}       
			});     
		});
    });
})(jQuery);
</script>
