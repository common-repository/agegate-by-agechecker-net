<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('AgeCheckerNet_AgeGate_Settings')):

	# Code specific to plugin settings page in admin panel
	#
	# Settings API Resource:
	# https://developer.wordpress.org/plugins/settings/using-settings-api/

	class AgeCheckerNet_AgeGate_Settings
	{

		public $base = 'agechecker_agegate';

		// STEP 1: Define setting variables
		public $active = true;
		public $type = '';
		public $min_age = 21;
		public $background = '';
		public $accent = '';
		public $logo_url = '';
		public $logo_height = '';
		public $logo_margin = '';
		public $title_text = '';
		public $body_text = '';
		public $remember_for = 25;
		public $advanced_config = '';

		public function __construct()
		{
			add_action('admin_init', array($this, 'init_settings'));

			add_action('admin_menu', array($this, 'options_page'));

			// STEP 2: Set setting variables to their current values, so the value can be easily accessed outside of the class
			$this->active = !is_null($this->get_setting($this->base . '_active'));
			$this->type = $this->get_setting($this->base . '_type');
			$this->min_age = $this->get_setting($this->base . '_min_age');
			$this->background = $this->get_setting($this->base . '_background');
			$this->accent = $this->get_setting($this->base . '_accent');
			$this->logo_url = $this->get_setting($this->base . '_logo_url');
			$this->logo_height = $this->get_setting($this->base . '_logo_height');
			$this->logo_margin = $this->get_setting($this->base . '_logo_margin');
			$this->title_text = $this->get_setting($this->base . '_title_text');
			$this->body_text = $this->get_setting($this->base . '_body_text');
			$this->remember_for = $this->get_setting($this->base . '_remember_for');
			$this->advanced_config = $this->get_setting($this->base . '_advanced_config');
		}

		function get_setting($option)
		{
			$options = get_option($this->base . '_options');

			if ($option === 'agechecker_agegate_active') {
				if (!is_array($options)) return null;
				if (!array_key_exists($option, $options)) return null;
			}

			if (empty($options)) return '';

			return $options[$option];
		}

		function init_settings()
		{
			// Defaults
			$modified_array = get_option($this->base . '_options');

			if (empty($modified_array)) {
				$modified_array = array(
					$this->base . '_active' => 'on',
					$this->base . '_type' => 'Accept Button',
					$this->base . '_min_age' => 21,
					$this->base . '_background' => "rgba(0,0,0,0.5)",
					$this->base . '_accent' => "linear-gradient(135deg, #7fc24c 0%,#04a1bf 100%)",
					$this->base . '_logo_url' => null,
					$this->base . '_logo_height' => "200px",
					$this->base . '_logo_margin' => null,
					$this->base . '_title_text' => "Age Verification",
					$this->base . '_body_text' => "You must be {min_age} or older to visit this website. Your age will be verified at checkout.",
					$this->base . '_remember_for' => 30,
					$this->base . '_advanced_config' => null,
				);
				update_option($this->base . '_options', $modified_array);
			}

			register_setting($this->base, $this->base . '_options');

			add_settings_section(
				$this->base . '_general',
				__('General Settings', 'agegate-by-agechecker-net'),
				array($this, $this->base . '_general_callback'),
				$this->base
			);

			# STEP 3: Declaring setting fields

			// Active
			add_settings_field(
				$this->base . '_active',
				// Option ID
				__('Active', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_checkbox'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_active',
					// Option ID
				)
			);

			// Type
			add_settings_field(
				$this->base . '_type',
				// Option ID
				__('Type', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_select'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_type',
					// Option ID
					'options' => array("Accept Button", "Yes/No Button", "Date of Birth Input (Month Selector)", "Date of Birth Input (Month Entry)"),
					'description' => 'The type of verification shown.'
				)
			);

			// Min Age
			add_settings_field(
				$this->base . '_min_age',
				// Option ID
				__('Minimum Age', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_number_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_min_age',
					// Option ID
					'description' => 'Years old. The minimum age customers must be. You can use this as a variable {min_age} in any of the customizable text.'
				)
			);

			// Background
			add_settings_field(
				$this->base . '_background',
				// Option ID
				__('Background', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_background',
					// Option ID
					'description' => "This appears behind the popup. Enter a hex color, gradient, or image URL. You can use any valid CSS background property."
				)
			);

			// Accent
			add_settings_field(
				$this->base . '_accent',
				// Option ID
				__('Accent', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_accent',
					// Option ID
					'description' => "This appears on the top of the popup and any buttons or inputs. Enter a hex color or gradient."
				)
			);

			// Logo URL
			add_settings_field(
				$this->base . '_logo_url',
				// Option ID
				__('Logo URL', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_logo_url',
					// Option ID
					'description' => "Full URL path to your logo image to be displayed inside the popup."
				)
			);

			// Logo Height
			add_settings_field(
				$this->base . '_logo_height',
				// Option ID
				__('Logo Height', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_logo_height',
					// Option ID
					'description' => "Height of the logo image container."
				)
			);

			// Logo Margin
			add_settings_field(
				$this->base . '_logo_margin',
				// Option ID
				__('Logo Margin', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_logo_margin',
					// Option ID
					'description' => "Margin space around the logo image container. You can use any valid CSS 'margin' property value."
				)
			);

			// Title Text
			add_settings_field(
				$this->base . '_title_text',
				// Option ID
				__('Title Text', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_title_text',
					// Option ID
					'description' => 'The text for the title of the popup.'
				)
			);

			// Body Text
			add_settings_field(
				$this->base . '_body_text',
				// Option ID
				__('Body Text', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_body_text',
					// Option ID
					'description' => "The text shown in the center of the popup."
				)
			);

			// Remember For
			add_settings_field(
				$this->base . '_remember_for',
				// Option ID
				__('Remember For', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_number_field'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_remember_for',
					// Option ID
					'description' => 'How many days the user should be remembered for.'
				)
			);

			// Advanced Config
			add_settings_field(
				$this->base . '_advanced_config',
				// Option ID
				__('Advanced Config', 'agegate-by-agechecker-net'),
				// Option Label
				array($this, $this->base . '_text_area'),
				// Field Type
				$this->base,
				$this->base . '_general',
				array(
					'id' => $this->base . '_advanced_config',
					// Option ID
					'description' => 'Additional config options, e.g. "branding":false',
					'style' => "width:400px;height:150px;"
				)
			);
		}

		function agechecker_agegate_general_callback($args)
		{
			?>
			<p id="<?php echo esc_attr($args['id']); ?>">
				<?php esc_html_e('For a visual example please visit: https://agechecker.net/age-gate/create', 'agegate-by-agechecker-net'); ?>
			</p>
			<?php
		}

		function agechecker_agegate_text_field($args)
		{
			$setting = $this->get_setting($args['id']);
			$description = isset($args['description']) ? $args['description'] : '';
			?>
			<input name="<?php echo esc_attr($this->base . "_options") . '[' . esc_attr($args['id']) . ']'; ?>"
				id="<?php echo esc_attr($args['id']); ?>" type="text"
				value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
			<?php if ($description)
				echo "<p>" . esc_html($description) . "</p>" ?>
			<?php
		}

		function agechecker_agegate_number_field($args)
		{
			$setting = $this->get_setting($args['id']);
			$description = isset($args['description']) ? $args['description'] : '';
			?>
			<input name="<?php echo esc_attr($this->base . "_options") . '[' . esc_attr($args['id']) . ']'; ?>"
				id="<?php echo esc_attr($args['id']); ?>" type="number"
				value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>" />
			<?php if ($description)
				echo "<p>" . esc_html($description) . "</p>" ?>
			<?php
		}

		function agechecker_agegate_checkbox($args)
		{
			$setting = $this->get_setting($args['id']);
			$description = isset($args['description']) ? $args['description'] : '';
			
			?>
			<input name="<?php echo esc_attr($this->base . "_options") . '[' . esc_attr($args['id']) . ']'; ?>"
				id="<?php echo esc_attr($args['id']); ?>" type="checkbox"
			<?php echo $setting === 'on' ? "checked" : null ?> />
			<?php if ($description)
				echo "<p>" . esc_html($description) . "</p>" ?>
			<?php
		}

		function agechecker_agegate_text_area($args)
		{
			$setting = $this->get_setting($args['id']);
			$description = isset($args['description']) ? $args['description'] : '';
			?>
			<textarea name="<?php echo esc_attr($this->base . "_options") . '[' . esc_attr($args['id']) . ']'; ?>"
				id="<?php echo esc_attr($args['id']); ?>" style=<?php  if ($args['style']) echo esc_attr($args['style']) ?>>
				<?php
				printf(
				/* translators: %s: Text area value */
				esc_html__( '%s', 'agegate-by-agechecker-net' ),
				esc_html( $setting )
				)
				?>
			</textarea>
			<?php if ($description)
				echo "<p>" . esc_html($description) . "</p>" ?>
			<?php
		}

		function agechecker_agegate_select($args)
		{
			$setting = $this->get_setting($args['id']);
			$description = isset($args['description']) ? $args['description'] : '';
			$options = isset($args['options']) ? $args['options'] : array();
			?>
			<select name="<?php echo esc_attr($this->base . "_options") . '[' . esc_attr($args['id']) . ']'; ?>"
				id="<?php echo esc_attr($args['id']); ?>" value="<?php echo isset($setting) ? esc_attr($setting) : ''; ?>">
				<?php foreach ($options as $option): ?>
					<option value="<?php 
						printf(
							/* translators: %s: Select input option value */
							esc_html__( '%s', 'agegate-by-agechecker-net' ),
							esc_html( $option )
					  	);
						?>" <?php echo $setting == $option ? 'selected' : ''; ?>>
						<?php
						printf(
							/* translators: %s: Select input option value */
							esc_html__( '%s', 'agegate-by-agechecker-net' ),
							esc_html( $option )
					  	);
						?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php if ($description)
				echo "<p>" . esc_html($description) . "</p>" ?>
			<?php
		}

		function options_page()
		{
			add_menu_page(
				'AgeGate',
				'AgeGate Options',
				'manage_options',
				$this->base,
				array($this, 'options_page_html')
			);
		}

		function options_page_html()
		{
			if (!current_user_can('manage_options')) {
				return;
			}

			if (isset($_GET['settings-updated'])) {
				add_settings_error($this->base . '_messages', $this->base . '_message', __('Settings Saved', 'agegate-by-agechecker-net'), 'updated');
			}

			settings_errors($this->base . '_messages');
			?>
			<div class="wrap">
				<h1>
					<?php echo esc_html(get_admin_page_title()); ?>
				</h1>
				<form action="options.php" method="post">
					<?php
					settings_fields($this->base);
					do_settings_sections($this->base);
					submit_button('Save Settings');
					?>
				</form>
			</div>
			<?php
		}
	}

endif;