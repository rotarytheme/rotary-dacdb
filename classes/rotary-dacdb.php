<?php

/**
 * This is the main class
 * @author paulosborn
 *
 */
class RotaryDaCDb {
	private $rotaryProfiles;
	private $rotaryAuth;

	function __construct() {
		register_activation_hook( __FILE__, array($this,'activate') );
		//register_deactivation_hook( __FILE__, array($this,'deactivate') );
		add_action( 'admin_init', array( $this, 'addOptions' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts_and_styles'));

		$options = get_option('rotary_dacdb');
		if ('yes' == $options['rotary_use_dacdb']) {
			$this->rotaryAuth = RotarySoapAuth::get_instance();
			//$this->rotaryProfiles->getUsers(new RotaryDacdbMemberData($this->rotaryAuth));
		}
		$this->setup_plugin_updates();
	}

	//activation creates a table to store rotary members user id from DacDb.
	//This will be used to delete users that are no longer Rotary members
	function activate() {
		global $wpdb;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		$table_name = $wpdb->prefix . 'rotarymembers';
		$sql =
		'CREATE TABLE ' . $table_name .'(
     		id int(11) unsigned NOT NULL auto_increment,
			dacdbuser varchar(60),
     		PRIMARY KEY  (id)
  		);';
		dbDelta($sql);
	}

	function deactivate() {
	}

	function setup_plugin_updates() {
		if ( is_admin() ) { // note the use of is_admin() to double check that this is happening in the admin
			$config = array(
					'slug' => ROTARY_DACDB_PLUGIN_FILE, // this is the slug of your plugin
					'proper_folder_name' => 'rotary-dacdb-master', // this is the name of the folder your plugin lives in
					'api_url' => 'https://api.github.com/repos/rotarytheme/rotary-dacdb', // the github API url of your github repo
					'raw_url' => 'https://raw.github.com/rotarytheme/rotary-dacdb/master', // the github raw url of your github repo
					'github_url' => 'https://github.com/rotarytheme/rotary-dacdb', // the github url of your github repo
					'zip_url' => 'https://github.com/rotarytheme/rotary-dacdb/zipball/master', // the zip url of the github repo
					'sslverify' => true, // wether WP should check the validity of the SSL cert when getting an update, see https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/2 and https://github.com/jkudish/WordPress-GitHub-Plugin-Updater/issues/4 for details
					'requires' => '3.5', // which version of WordPress does your plugin require?
					'tested' => '4.3.1', // which version of WordPress is your plugin tested up to?
					'readme' => 'README.md' // which file to use as the readme for the version number
			);
			new WP_GitHub_Updater( $config );
		}
	}


	/*
	 register if DaCdb should be used in the general settings
	*/
	function addOptions() {
		//register a new setting for DacDb along with a validate callback
		register_setting('general', 'rotary_dacdb', array($this, 'validate_settings'));
		//add a section for DacDb to the general page
		add_settings_section('rotary_settings_section', 'Rotary Options', array($this, 'rotary_settings_page'), 'general' );
		//add fields for DacDb to the section just added to the general page
		add_settings_field('rotary_use_dacdb', 'Use DacDb for membership?', array($this, 'rotary_form_field'), 'general', 'rotary_settings_section', array('fieldName' => 'rotary_use_dacdb'));
		add_settings_field('rotary_instructions', '', array($this, 'rotary_form_field'), 'general', 'rotary_settings_section', array('fieldName' => 'rotary_instructions'));

		add_settings_field('rotary_dacdb_district', '<span class="dacdb">Rotary District Number</span>', array($this, 'rotary_form_field'), 'general', 'rotary_settings_section', array('fieldName' => 'rotary_dacdb_district'));
		add_settings_field('rotary_dacdb_club', '<span class="dacdb">Rotary/Rotaract Club Number</span>', array($this, 'rotary_form_field'), 'general', 'rotary_settings_section', array('fieldName' => 'rotary_dacdb_club'));
		add_settings_field('rotary_dacdb_club_name', '<span class="nodacdb">Rotary/Rotaract Club Name</span>', array($this, 'rotary_form_field'), 'general', 'rotary_settings_section', array('fieldName' => 'rotary_dacdb_club_name'));
		//add filter to add a setup link for the plugin on the plugin page
		add_filter('plugin_action_links_'. ROTARY_DACDB_PLUGIN_FILE, array($this, 'rotary_base_plugin_link'), 10, 4);
	}

	/*
	 UI for DaCdb settings
	*/

	function rotary_settings_page() {
		echo '<p>Rotary Membership</p>';
	}
	function rotary_form_field($args) {
		$currFieldName = $args['fieldName'];
		$options = get_option('rotary_dacdb');
		if ('yes' == $options['rotary_use_dacdb']) {
			$disabled = '';
		}
		switch ($currFieldName) {
			case 'rotary_use_dacdb':
				$yeschecked = '';
				$nochecked = '';
				if ('yes' == $options['rotary_use_dacdb']) {
					$yeschecked = 'checked="checked"';
					$noschecked = '';
				}
				else {
					$noschecked = 'checked="checked"';
					$yeschecked = '';
				}
				$useDacDb = '<p id="rotary_use_dacdb">Yes <input type="radio" name="rotary_dacdb[rotary_use_dacdb]" value="yes" '.$yeschecked.' />' .
						' No <input type="radio" name="rotary_dacdb[rotary_use_dacdb]" value="no"  '.$noschecked.' /></p>' ;
				echo $useDacDb;
				break;
			case 'rotary_dacdb_district':
				$dacdbDistrict = '<input type="number" class="dacdb" name="rotary_dacdb[rotary_dacdb_district]" id="rotary_dacdb_district" value="'.esc_attr( $options['rotary_dacdb_district'] ) .'" class="regular-text"/>';
				echo $dacdbDistrict;
				break;
			case 'rotary_dacdb_club':
				$dacdbClub = '<input type="number" class="dacdb" name="rotary_dacdb[rotary_dacdb_club]" id="rotary_dacdb_club" value="'.esc_attr( $options['rotary_dacdb_club'] ) .'" class="regular-text"/>';
				echo $dacdbClub;
				break;
			case 'rotary_dacdb_club_name':
				$dacdbClubName = '<input type="text" class="nodacdb" name="rotary_dacdb[rotary_dacdb_club_name]" id="rotary_dacdb_club_name" value="'.esc_attr( $options['rotary_dacdb_club_name'] ) .'" class="regular-text"/>';
				echo $dacdbClubName;
				break;
			case 'rotary_instructions':
				echo '<p id="rotary_instructions" class="dacdb">Changes will take effect after you log out and then log back in with your <strong>DacDb</strong> username and password</p>';
				break;
		}
	}
	/*
	 adds a link from the plugin the the general settings area
	*/
	function rotary_base_plugin_link($actions, $plugin_file) {
		static $this_plugin;
		if( !$this_plugin ) {
			$this_plugin = ROTARY_DACDB_PLUGIN_FILE;
		}
		if( $plugin_file == $this_plugin ){
			$settingsLink = '<a href="'. admin_url('options-general.php#rotary_use_dacdb'). '">Setup</a>';
			return array_merge(
					array(
							'settings' => $settingsLink
					),
					$actions
			);
		}
	}
	function enqueue_scripts_and_styles() {
		wp_enqueue_script( 'rotarymembership', ROTARY_DACDB_JAVASCRIPT_URL . 'rotarymembership.js' );
		wp_enqueue_media();
		wp_enqueue_script( 'jquery-ui-datepicker');
		wp_enqueue_script( 'jquery-ui-dialog');
		wp_register_style( 'rotary-style', ROTARY_DACDB_CSS_URL . 'rotarymembership.css' ,false, 0.1);
		wp_enqueue_style( 'rotary-style' );
	}
	function validate_settings($input) {
		// var_dump($input);
		//exit;
		if (!current_user_can('install_plugins')) {
			add_settings_error('rotary_dacdb', '100','You cannot install this plugin','error');
			return false;
		}
		else {
			$clean = array();
			if ('yes' == $input['rotary_use_dacdb']) {
				$clean[0] = absint(strip_tags($input['rotary_dacdb_district']));
				$clean[1] = absint(strip_tags($input['rotary_dacdb_club']));
				if ($clean[0] && $clean[1] ) {
					return $input;
				}
				else {
					add_settings_error('rotary_dacdb', '100','Please enter a valid district and club number','error');
					return false;
				}
			}
			else {
				$clean[2] = strip_tags($input['rotary_dacdb_club_name']);
				if ($clean[2]) {
					return $input;
				}
				else {
					add_settings_error('rotary_dacdb', '100','Please enter a valid club name','error');
					return false;
				}
			}
		}

	}
} // end class