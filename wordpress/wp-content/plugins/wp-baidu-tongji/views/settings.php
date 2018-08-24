	<div class="wbt-notice notice notice-info" v-if="message">
		<a class="notice-dismiss" @click="closeNotice">
			<span class="screen-reader-text"><?php _e('Close'); ?></span>
		</a>
		<p v-text="message"></p>
	</div>
	<div class="wrap">
		<form action="" method="post">
			<table class="form-table">
				<tbody>
					<tr class="login_url">
						<th><label for="login_url">login_url</label></th>
						<td><input type="text" id="login_url" v-model="options.login_url" class="regular-text"></td>
					</tr>
					<tr class="api_url">
						<th><label for="api_url">api_url</label></th>
						<td><input type="text" id="api_url" v-model="options.api_url" class="regular-text"></td>
					</tr>
					<tr class="username">
						<th><label for="username">username</label></th>
						<td><input type="text" id="username" v-model="options.username" class="regular-text"></td>
					</tr>
					<tr class="password">
						<th><label for="password">password</label></th>
						<td>
							<input :type="showPassword?'password':'text'" id="password" v-model="options.password" class="regular-text">
							<button @click="showPassword=!showPassword" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
								<span class="dashicons" :class="showPassword?'dashicons-visibility':'dashicons-hidden'"></span>	
								<span class="text" v-text="showPassword?'<?php _e('Show'); ?>':'<?php _e('Hide'); ?>'"></span>
							</button>
						</td>
					</tr>
					<tr class="token">
						<th><label for="token">token</label></th>
						<td>
							<input :type="showToken?'password':'text'" id="token" v-model="options.token" class="regular-text">
							<button @click="showToken=!showToken" type="button" class="button button-secondary wp-hide-pw hide-if-no-js">
								<span class="dashicons" :class="showToken?'dashicons-visibility':'dashicons-hidden'"></span>	
								<span class="text" v-text="showToken?'<?php _e('Show'); ?>':'<?php _e('Hide'); ?>'"></span>
							</button>
						</td>
					</tr>
					<tr class="uuid">
						<th><label for="uuid">uuid</label></th>
						<td><input type="text" id="uuid" v-model="options.uuid" class="regular-text"></td>
					</tr>
					<tr class="account_type">
						<th><label for="account_type">account_type</label></th>
						<td><input type="text" id="account_type" v-model="options.account_type" class="regular-text"></td>
					</tr>
				</tbody>
			</table>
			<table>
				<tbody>
					<tr>
						<td>
							<p name="submit" @click="setOptions" class="button button-primary"><?php echo __('Save'); ?></p>
						</td>
						<td><p id="response"></p></td>
					<tr>
				</tbody>
			</table>
		</form>
	</div>
</div>