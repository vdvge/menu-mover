<?php
/*
Plugin Name: Menu Mover
Description: Versteckt das Menü beim Herunterscrollen und zeigt es beim Hochscrollen wieder an.
Version: 1.0
Author: Boris Wallbruch
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class MenuMover {
    public function __construct() {
        // Einstellungen ins Dashboard hinzufügen
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'settings_init'));

        // JavaScript und CSS laden
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));

        // Einstellungen speichern
        add_option('menu_mover_selector', '');
        add_option('menu_mover_speed', '600');
    }

    public function add_admin_menu() {
        add_options_page('Menu Mover Einstellungen', 'Menu Mover', 'manage_options', 'menu-mover', array($this, 'settings_page'));
    }

    public function settings_init() {
        register_setting('menu_mover_settings', 'menu_mover_selector');
        register_setting('menu_mover_settings', 'menu_mover_speed');

        add_settings_section(
            'menu_mover_section', 
            __('Menü-Einstellungen', 'menu-mover'), 
            null, 
            'menu_mover_settings'
        );

        add_settings_field(
            'menu_mover_selector', 
            __('Menü-Klasse oder ID', 'menu-mover'), 
            array($this, 'selector_render'), 
            'menu_mover_settings', 
            'menu_mover_section'
        );

        add_settings_field(
            'menu_mover_speed', 
            __('Ausblend-Geschwindigkeit (ms)', 'menu-mover'), 
            array($this, 'speed_render'), 
            'menu_mover_settings', 
            'menu_mover_section'
        );
    }

    public function selector_render() {
        $value = get_option('menu_mover_selector', '');
        echo '<input type="text" name="menu_mover_selector" value="' . esc_attr($value) . '" placeholder="#menu oder .menu">';
    }

    public function speed_render() {
        $value = get_option('menu_mover_speed', '600');
        echo '<input type="number" name="menu_mover_speed" value="' . esc_attr($value) . '" min="100" step="100">';
    }

    public function settings_page() {
        ?>
        <form action="options.php" method="post">
            <?php
            settings_fields('menu_mover_settings');
            do_settings_sections('menu_mover_settings');
            submit_button();
            ?>
        </form>
        <?php
    }

    public function enqueue_scripts() {
        wp_enqueue_script('menu-mover-script', plugin_dir_url(__FILE__) . 'menu-mover.js', array('jquery'), null, true);

        // PHP-Optionen an JavaScript übergeben
        wp_localize_script('menu-mover-script', 'menuMoverOptions', array(
            'selector' => get_option('menu_mover_selector', '#main-menu'), // Standardmenü
            'speed' => get_option('menu_mover_speed', '600')
        ));
    }
}

new MenuMover();
