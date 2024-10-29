<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('AgeCheckerNet_AgeGate_Popup')):

    # Code specific to hooking AgeGate script into site's header

    class AgeCheckerNet_AgeGate_Popup
    {

        private $settings = null;

        public function __construct($settings)
        {
            $this->settings = $settings;

            add_action('wp_head', array(
                $this,
                'add_script_head'
            ), 1);
        }

        public function add_script_head()
        {
            // Return if plugin is not active
            if (!$this->settings->active)
                return;

                $inlineJs = "window.AgeCheckerAgeGateConfig = {";
                if ($this->settings->type) {
                    if ($this->settings->type === 'Yes/No Button') {
                        $inlineJs .= '"type":"yesno",';
                    }
                    if ($this->settings->type === 'Date of Birth Input (Month Selector)') {
                        $inlineJs .= '"type":"dob",';
                    }
                    if ($this->settings->type === 'Date of Birth Input (Month Entry)') {
                        $inlineJs .= '"type":"dobinput",';
                    }
                }   
                
                if ($this->settings->min_age) {
                    $inlineJs .= '"minAge":' . esc_html($this->settings->min_age) . ',';
                }
                if ($this->settings->background) {
                    $inlineJs .= '"background":"' . esc_html($this->settings->background) . '",';
                }
                if ($this->settings->accent) {
                    $inlineJs .= '"accent":"' . esc_html($this->settings->accent) . '",';
                }
                if ($this->settings->logo_url) {
                    $inlineJs .= '"logoUrl":"' . esc_html($this->settings->logo_url) . '",';
                }
                if ($this->settings->logo_height) {
                    $inlineJs .= '"logoHeight":"' . esc_html($this->settings->logo_height) . '",';
                }
                if ($this->settings->logo_margin) {
                    $inlineJs .= '"logoMargin":"' . esc_html($this->settings->logo_margin) . '",';
                }
                if ($this->settings->title_text) {
                    $inlineJs .= '"titleText":"' . esc_html($this->settings->title_text) . '",';
                }
                if ($this->settings->body_text) {
                    $inlineJs .= '"bodyText":"' . esc_html($this->settings->body_text) . '",';
                }
                if ($this->settings->remember_for) {
                    $inlineJs .= '"rememberFor":' . esc_html($this->settings->remember_for) . ',';
                }

                if ($this->settings->advanced_config) {
                    $inlineJs .= wp_kses_post($this->settings->advanced_config);
                }
                $inlineJs .= "};";
            
                wp_enqueue_script("agechecker-agegate-popup", "https://cdn.agechecker.net/static/age-gate/v1/age-gate.js");
                wp_add_inline_script('agechecker-agegate-popup', $inlineJs, 'before');
        }
    }

endif;