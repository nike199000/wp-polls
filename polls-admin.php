<?php

class Polls_Admin {

	### Function: Poll Administration Menu
	function __construct() {
		add_action( 'init', array( &$this, 'poll_tinymce_addbuttons' ) );

		add_action( 'admin_menu', array( &$this, 'poll_menu' ) );
		add_action( 'admin_enqueue_scripts', array( &$this, 'poll_scripts_admin' ) );

		add_action( 'admin_footer', array( &$this, 'poll_footer_admin' ) );
	}

	function poll_menu() {
		add_menu_page( __( 'Polls', 'wp-polls' ), __( 'Polls', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-manager.php', '', plugins_url( 'wp-polls/images/poll.png' ) );

		add_submenu_page( 'wp-polls/polls-manager.php', __( 'Manage Polls', 'wp-polls' ), __( 'Manage Polls', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-manager.php' );
		add_submenu_page( 'wp-polls/polls-manager.php', __( 'Add Poll', 'wp-polls' ), __( 'Add Poll', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-add.php' );
		add_submenu_page( 'wp-polls/polls-manager.php', __( 'Poll Options', 'wp-polls' ), __( 'Poll Options', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-options.php' );
		add_submenu_page( 'wp-polls/polls-manager.php', __( 'Poll Templates', 'wp-polls' ), __( 'Poll Templates', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-templates.php' );
		add_submenu_page( 'wp-polls/polls-manager.php', __( 'Uninstall WP-Polls', 'wp-polls' ), __( 'Uninstall WP-Polls', 'wp-polls' ), 'manage_polls', 'wp-polls/polls-uninstall.php' );
	}

	### Function: Enqueue Polls Stylesheets/JavaScripts In WP-Admin
	function poll_scripts_admin( $hook_suffix ) {
		$poll_admin_pages = array( 'wp-polls/polls-manager.php', 'wp-polls/polls-add.php', 'wp-polls/polls-options.php', 'wp-polls/polls-templates.php', 'wp-polls/polls-uninstall.php' );

		if( in_array( $hook_suffix, $poll_admin_pages ) ) {
			wp_enqueue_style( 'wp-polls-admin', plugins_url('wp-polls/polls-admin-css.css'), false, '2.63', 'all' );
			wp_enqueue_script( 'wp-polls-admin', plugins_url('wp-polls/polls-admin-js.js'), array('jquery'), '2.63', true );

			wp_localize_script( 'wp-polls-admin', 'pollsAdminL10n', array(
				'admin_ajax_url' => admin_url( 'admin-ajax.php' ),
				'text_direction' => ( is_rtl() ) ? 'left' : 'right',
				'text_delete_poll' => __( 'Delete Poll', 'wp-polls' ),
				'text_no_poll_logs' => __( 'No poll logs available.', 'wp-polls' ),
				'text_delete_all_logs' => __( 'Delete All Logs', 'wp-polls' ),
				'text_checkbox_delete_all_logs' => __( 'Please check the \\\'Yes\\\' checkbox if you want to delete all logs.', 'wp-polls' ),
				'text_delete_poll_logs' => __( 'Delete Logs For This Poll Only', 'wp-polls' ),
				'text_checkbox_delete_poll_logs' => __( 'Please check the \\\'Yes\\\' checkbox if you want to delete all logs for this poll ONLY.', 'wp-polls' ),
				'text_delete_poll_ans' => __( 'Delete Poll Answer', 'wp-polls' ),
				'text_open_poll' => __( 'Open Poll', 'wp-polls' ),
				'text_close_poll' => __( 'Close Poll', 'wp-polls' ),
				'text_answer' => __( 'Answer', 'wp-polls' ),
				'text_remove_poll_answer' => __( 'Remove', 'wp-polls' )
			) );
		}
	}

	### Function: Displays Polls Footer In WP-Admin
	function poll_footer_admin() {
		$screen = get_current_screen();

		if( 'post' === $screen->base ) {
			// Javascript Code Courtesy Of WP-AddQuicktag (http://bueltge.de/wp-addquicktags-de-plugin/120/)
			echo '<script type="text/javascript">'."\n";
			echo "/* <![CDATA[ */\n";
			echo "\t".'var pollsEdL10n = {'."\n";
			echo "\t\t".'enter_poll_id: "'.esc_js(__('Enter Poll ID', 'wp-polls')).'",'."\n";
			echo "\t\t".'enter_poll_id_again: "'.esc_js(__('Error: Poll ID must be numeric', 'wp-polls')).'\n\n'.esc_js(__('Please enter Poll ID again', 'wp-polls')).'",'."\n";
			echo "\t\t".'poll: "'.esc_js(__('Poll', 'wp-polls')).'",'."\n";
			echo "\t\t".'insert_poll: "'.esc_js(__('Insert Poll', 'wp-polls')).'"'."\n";
			echo "\t".'};'."\n";
			echo "\t".'function insertPoll(where, myField) {'."\n";
			echo "\t\t".'var poll_id = jQuery.trim(prompt(pollsEdL10n.enter_poll_id));'."\n";
			echo "\t\t".'while(isNaN(poll_id)) {'."\n";
			echo "\t\t\t".'poll_id = jQuery.trim(prompt(pollsEdL10n.enter_poll_id_again));'."\n";
			echo "\t\t".'}'."\n";
			echo "\t\t".'if (poll_id >= -1 && poll_id != null && poll_id != "") {'."\n";
			echo "\t\t\t".'if(where == \'code\') {'."\n";
			echo "\t\t\t\t".'edInsertContent(myField, \'[poll id="\' + poll_id + \'"]\');'."\n";
			echo "\t\t\t".'} else {'."\n";
			echo "\t\t\t\t".'return \'[poll id="\' + poll_id + \'"]\';'."\n";
			echo "\t\t\t".'}'."\n";
			echo "\t\t".'}'."\n";
			echo "\t".'}'."\n";
			echo "\t".'if(document.getElementById("ed_toolbar")){'."\n";
			echo "\t\t".'edButtons[edButtons.length] = new edButton("ed_poll",pollsEdL10n.poll, "", "","");'."\n";
			echo "\t\t".'jQuery(document).ready(function($){'."\n";
			echo "\t\t\t".'$(\'#qt_content_ed_poll\').replaceWith(\'<input type="button" id="qt_content_ed_poll" accesskey="" class="ed_button" onclick="insertPoll(\\\'code\\\', edCanvas);" value="\' + pollsEdL10n.poll + \'" title="\' + pollsEdL10n.insert_poll + \'" />\');'."\n";
			echo "\t\t".'});'."\n";
			echo "\t".'}'."\n";
			echo '/* ]]> */'."\n";
			echo '</script>'."\n";
		}
	}


	### Function: Add Quick Tag For Poll In TinyMCE >= WordPress 2.5
	function poll_tinymce_addbuttons() {
		if( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			return;
		}

		if( get_user_option('rich_editing') == 'true' ) {
			add_filter( 'mce_external_plugins', array( 'poll_tinymce_addplugin' ) );
			add_filter( 'mce_buttons', 'poll_tinymce_registerbutton' );
		}
	}

	function poll_tinymce_registerbutton( $buttons ) {
		array_push( $buttons, 'separator', 'polls' );

		return $buttons;
	}

	function poll_tinymce_addplugin( $plugin_array ) {
		$plugin_array['polls'] = plugins_url('wp-polls/tinymce/plugins/polls/editor_plugin.js');

		return $plugin_array;
	}
}