<?php
/**
 * Created by PhpStorm.
 * User: Clément
 */

/*
 * Add my new menu to the Admin Control Panel
 */

add_action( 'admin_menu', 'Kayndu_Add_My_Admin_Link' );

// Add a new top level menu link to the ACP
function Kayndu_Add_My_Admin_Link()
{
	add_menu_page(
		'Resume - Kayndu plugin',
		'Resume',
		'manage_options',
		'kayndu_resume',
		'kayndu_resume_content_html'

	);
}

// Option page html code to display
function kayndu_resume_content_html()
{
	// check user capabilities
	if (!current_user_can( 'manage_options' ))
	{
		return;
	}

	//Setting WP handle
	$settings 	= get_option( 'kayndu_fields' );
	?>

	<div>
		<h2>Kayndu Resume</h2>
		<form method="post" action="options.php">
			<?php settings_fields( 'kayndu_options_group' ); ?>
			<table>
				<tr>
					<th scope="row"><label for="kayndu_option_name">Name</label></th>
					<td><input type="text" id="kayndu_option_name" name="kayndu_fields[name]" value="<?php echo $settings['name']; ?>" /></td>
				</tr>
				<tr>
					<th scope="row">
						<label for="kayndu_option_resume">Resume</label>
					</th>
					<td>
						<textarea name="kayndu_fields[resume]" id="kayndu_option_resume"></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row"><label for="kayndu_option_sendCopy">Send Copy</label></th>
					<td>
						<input type="checkbox" id="kayndu_option_sendCopy" name="kayndu_fields[sendCopy]" onclick="kayndu_showEmailField()"/>
					</td>
				</tr>
			</table>
			<table>
				<tr class="hidden" id="mailField">
					<th scope="col"><label for="kayndu_option_mail" >Enter your mail address</label></th>
					<td>
						<input type="email" id="kayndu_option_mail" name="kayndu_fields[mail]" value="<?php sanitize_email( $settings['mail'] ); ?>" />
					</td>
				</tr>
			</table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}

//Data check and send mail
function Kayndu_callback_function( $input ){

	$message = "Thank you for sending your resume";
	$type = 'updated';

	if ( empty($input['resume']) )
	{
		$message = "Can't send an empty resume";
		$type = 'error';
	}

	if ( empty($input['name']) )
	{
		$message = "Please give us your name at least";
		$type = 'error';
	}

	if ( isset( $input['sendCopy'] ) && ( $input['sendCopy']) == 'on' )
	{
		$input['mail'] = is_email($input['mail']);

		if ( !$input['mail'] )
		{
			$message = "This can't be your real mail address";
			$type = 'error';

		} else { // if mail address is correct, send mail

			$to = $input['mail'];
			$subject = "Your resume has been sent to Kayndu";
			$content = $input['resume'];
			add_filter( 'wp_mail_content_type', 'set_html_content_type' );
			$status = wp_mail($to, $subject, $content);
			remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
			function set_html_content_type() {
				return 'text/html';
			}

			$message = "Thank you for sending your resume, a copy has been sent to your by mail.";

			if ( !$status )
			{
				$message = "Sorry your mail wasn't send correctly, please try again";
				$type = 'error';

			}
		}
	}

	// error and message handling according to the form sent
	if ( $type == "error")
	{
		add_settings_error(
			'kayndu_fields',
			esc_attr( 'settings_updated' ),
			$message,
			$type
		);
		return false;
	} else {
		add_settings_error(
			'kayndu_fields',
			esc_attr( 'settings_updated' ),
			$message,
			$type
		);
		return $input;
	}
}

//Display error from the form
function display_errors() {
	settings_errors( 'kayndu_fields' );
}
add_action( 'admin_notices', 'display_errors' );

//Save data in DB
function Kayndu_register_settings()
{
	register_setting( 'kayndu_options_group', 'kayndu_fields', 'Kayndu_callback_function');
}
add_action( 'admin_init', 'Kayndu_register_settings' );
?>