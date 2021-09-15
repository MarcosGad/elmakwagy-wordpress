<?php if ( !defined('ABSPATH') ) {
    die;
} // Cannot access directly.
/**
 *
 * Options class
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( !class_exists('OVIC_Options') ) {
    class OVIC_Options extends OVIC_Abstract
    {
        // constants
        public $unique       = '';
        public $abstract     = 'options';
        public $notice       = false;
        public $errors       = array();
        public $options      = array();
        public $save_options = array();
        public $tabs         = array();
        public $sections     = array();
        public $fields       = array();
        // default args
        public $args = array(
            'option_name'             => '_ovic_customize_options',
            // framework title
            'framework_title'         => 'Ovic Framework <small>by Kutethemes</small>',
            'framework_class'         => '',

            // menu settings
            'menu_title'              => 'Framework',
            'menu_type'               => 'menu',                             // menu, submenu, options, theme, etc.
            'menu_slug'               => 'ovic_theme_options',
            'menu_icon'               => '',
            'menu_capability'         => 'manage_options',
            'menu_hidden'             => false,
            'menu_position'           => null,

            // menu extras
            'admin_bar_menu_icon'     => 'dashicons-admin-appearance',
            'admin_bar_menu_priority' => 80,
            'show_bar_menu'           => true,
            'show_network_menu'       => false,

            'show_search'             => true,
            'show_reset'              => true,
            'show_reset_all'          => true,
            'show_footer'             => true,
            'show_all_options'        => true,
            'show_form_warning'       => true,
            'ajax_save'               => false,
            'sticky_header'           => true,
            'save_defaults'           => true,

            // footer
            'footer_text'             => '',
            'footer_after'            => '',

            // database model
            'database'                => 'options',
            // options, transient, theme_mod, network
            'transient_time'          => 0,

            // contextual help
            'contextual_help'         => array(),
            'contextual_help_sidebar' => '',

            // typography options
            'enqueue_webfont'         => true,
            'async_webfont'           => false,

            // theme
            'theme'                   => 'dark',
            'class'                   => '',

            // others
            'output_css'              => true,

            // external default values
            'defaults'                => array(),
        );

        // run framework construct
        public function __construct( $args = array(), $options = array() )
        {
            $this->args    = apply_filters("ovic_framework_{$this->unique}_settings", wp_parse_args($args, $this->args), $this);
            $this->options = apply_filters("ovic_framework_{$this->unique}_options", $options, $this);

            // Get options framework
            $this->unique   = $this->args['option_name'];
            $this->tabs     = $this->get_tabs($this->options);
            $this->fields   = $this->get_fields($this->options);
            $this->sections = $this->get_sections($this->options);

            // Default framework
            $this->get_options();
            $this->set_options();
            $this->save_defaults();

            // Actions framework
            add_action('admin_menu', array( &$this, 'add_admin_menu' ));
            add_action('admin_bar_menu', array( &$this, 'add_admin_bar_menu' ), $this->args['admin_bar_menu_priority']);

            // Ajax save
            add_action('wp_ajax_ovic_' . $this->unique . '_ajax_save', array( &$this, 'ajax_save' ));

            // wp enqueue for typography and output css
            parent::__construct();
        }

        // instance of framework
        public static function instance( $args = array(), $options = array() )
        {
            return new self($args, $options);
        }

        public function ajax_save()
        {
            if ( !empty($_POST['data']) ) {
                $_POST = json_decode(stripslashes($_POST['data']), true);

                if ( wp_verify_nonce(ovic_get_var('ovic_options_nonce' . $this->unique), 'ovic_options_nonce') ) {
                    $this->set_options();

                    wp_send_json_success(array(
                        'success' => true, 'notice' => $this->notice, 'errors' => $this->errors
                    ));
                }
            }

            wp_send_json_error(array(
                'success' => false, 'error' => esc_html__('Error while saving.', 'ovic-addon-toolkit')
            ));
        }

        // save defaults and set new fields value to main options
        public function save_defaults()
        {
            $tmp_options = $this->save_options;

            foreach ( $this->fields as $field ) {
                if ( !empty($field['id']) ) {
                    $field_default = ( isset($field['default']) ) ? $field['default'] : '';
                    $field_value   = ( isset($this->save_options[$field['id']]) ) ? $this->save_options[$field['id']] : $field_default;

                    $this->save_options[$field['id']] = $field_value;
                }
            }

            if ( $this->args['save_defaults'] && empty($tmp_options) ) {
                $this->save_options($this->save_options);
            }
        }

        // set options
        public function set_options()
        {
            $request = ovic_get_var($this->unique);

            if ( empty($request) ) {
                return false;
            }

            $transient  = ovic_get_var('ovic_transient');
            $section_id = ( !empty($transient['section']) ) ? $transient['section'] : '';

            if ( wp_verify_nonce(ovic_get_var('ovic_options_nonce' . $this->unique), 'ovic_options_nonce') ) {
                // import data
                if ( !empty($transient['ovic_import_data']) ) {
                    $import_data = json_decode(stripslashes(trim($transient['ovic_import_data'])), true);
                    $request     = ( is_array($import_data) ) ? $import_data : array();

                    $this->notice = esc_html__('Success. Imported backup options.', 'ovic-addon-toolkit');
                } else {
                    if ( !empty($transient['reset']) ) {
                        foreach ( $this->fields as $field ) {
                            if ( !empty($field['id']) ) {
                                if ( isset($field['default']) ) {
                                    $request[$field['id']] = $field['default'];
                                } else {
                                    $request[$field['id']] = '';
                                }
                            }
                        }

                        $this->notice = esc_html__('Default options restored.', 'ovic-addon-toolkit');
                    } else {
                        if ( !empty($transient['reset_section']) && !empty($section_id) ) {
                            if ( !empty($this->sections[$section_id - 1]['fields']) ) {
                                foreach ( $this->sections[$section_id - 1]['fields'] as $field ) {
                                    if ( !empty($field['id']) ) {
                                        if ( isset($field['default']) ) {
                                            $request[$field['id']] = $field['default'];
                                        } else {
                                            $request[$field['id']] = '';
                                        }
                                    }
                                }
                            }

                            $this->notice = esc_html__('Default options restored for only this section.',
                                'ovic-addon-toolkit');
                        } else {
                            // sanitize and validate
                            foreach ( $this->fields as $field ) {
                                if ( !empty($field['id']) ) {
                                    // sanitize
                                    if ( !empty($field['sanitize']) ) {
                                        $sanitize              = $field['sanitize'];
                                        $value_sanitize        = isset($request[$field['id']]) ? $request[$field['id']] : '';
                                        $request[$field['id']] = call_user_func($sanitize, $value_sanitize);
                                    }

                                    // validate
                                    if ( !empty($field['validate']) ) {
                                        $value_validate = isset($request[$field['id']]) ? $request[$field['id']] : '';
                                        $has_validated  = call_user_func($field['validate'], $value_validate);

                                        if ( !empty($has_validated) ) {
                                            $request[$field['id']]      = ( isset($this->save_options[$field['id']]) ) ? $this->save_options[$field['id']] : '';
                                            $this->errors[$field['id']] = $has_validated;
                                        }
                                    }

                                    // auto sanitize
                                    if ( !isset($request[$field['id']]) || is_null($request[$field['id']]) ) {
                                        $request[$field['id']] = '';
                                    }
                                }
                            }
                        }
                    }
                }

                // ignore nonce requests
                if ( isset($request['_nonce']) ) {
                    unset($request['_nonce']);
                }

                $request = wp_unslash($request);

                $request = apply_filters("ovic_{$this->unique}_save", $request, $this);

                do_action("ovic_{$this->unique}_save_before", $request, $this);

                $this->save_options = $request;

                $this->save_options($request);

                do_action("ovic_{$this->unique}_save_after", $request, $this);

                if ( empty($this->notice) ) {
                    $this->notice = esc_html__('Settings saved.', 'ovic-addon-toolkit');
                }
            } else {
                wp_die(esc_html__('Error while saving.', 'ovic-addon-toolkit'));
            }

            return true;
        }

        // save options database
        public function save_options( $request )
        {
            if ( $this->args['database'] === 'transient' ) {
                set_transient($this->unique, $request, $this->args['transient_time']);
            } else {
                if ( $this->args['database'] === 'theme_mod' ) {
                    set_theme_mod($this->unique, $request);
                } else {
                    if ( $this->args['database'] === 'network' ) {
                        update_site_option($this->unique, $request);
                    } else {
                        update_option($this->unique, $request);
                    }
                }
            }

            do_action("ovic_{$this->unique}_saved", $request, $this);
        }

        // get options from database
        public function get_options()
        {
            if ( $this->args['database'] === 'transient' ) {
                $this->save_options = get_transient($this->unique);
            } else {
                if ( $this->args['database'] === 'theme_mod' ) {
                    $this->save_options = get_theme_mod($this->unique);
                } else {
                    if ( $this->args['database'] === 'network' ) {
                        $this->save_options = get_site_option($this->unique);
                    } else {
                        $this->save_options = get_option($this->unique);
                    }
                }
            }

            if ( empty($this->save_options) ) {
                $this->save_options = array();
            }

            return $this->save_options;
        }

        // add admin bar menu
        public function add_admin_bar_menu( $wp_admin_bar )
        {
            if ( !empty($this->args['show_bar_menu']) && empty($this->args['menu_hidden']) && !is_network_admin() ) {
                global $submenu;
                $menu_slug = $this->args['menu_slug'];
                $menu_icon = ( !empty($this->args['admin_bar_menu_icon']) ) ? '<span class="ovic-ab-icon ab-icon ' . $this->args['admin_bar_menu_icon'] . '" style="line-height:24px;"></span>' : '';
                $wp_admin_bar->add_node(array(
                        'id'    => $menu_slug,
                        'title' => $menu_icon . $this->args['menu_title'],
                        'href'  => ( is_network_admin() ) ? network_admin_url('admin.php?page=' . $menu_slug) : admin_url('admin.php?page=' . $menu_slug),
                    )
                );
                if ( !empty($this->args['show_network_menu']) ) {
                    $wp_admin_bar->add_node(array(
                            'parent' => 'network-admin',
                            'id'     => $menu_slug . '-network-admin',
                            'title'  => $menu_icon . $this->args['menu_title'],
                            'href'   => network_admin_url('admin.php?page=' . $menu_slug),
                        )
                    );
                }
            }
        }

        // wp api: admin menu
        public function add_admin_menu()
        {
            $defaults = array(
                'menu_parent'     => '',
                'menu_title'      => '',
                'menu_type'       => '',
                'menu_slug'       => '',
                'menu_icon'       => '',
                'menu_capability' => 'manage_options',
                'menu_position'   => null,
            );
            $args     = wp_parse_args($this->args, $defaults);
            if ( $args['menu_type'] == 'submenu' ) {
                $menu_page = call_user_func('add_' . $args['menu_type'] . '_page', $args['menu_parent'],
                    $args['menu_title'], $args['menu_title'], $args['menu_capability'], $args['menu_slug'],
                    array( &$this, 'add_options_html' ));
            } else {
                $menu_page = call_user_func('add_' . $args['menu_type'] . '_page', $args['menu_title'],
                    $args['menu_title'], $args['menu_capability'], $args['menu_slug'],
                    array( &$this, 'add_options_html' ), $args['menu_icon'], $args['menu_position']);
            }

            add_action('load-' . $menu_page, array( &$this, 'add_page_on_load' ));
        }

        public function add_page_on_load()
        {
            if ( !empty($this->args['contextual_help']) ) {
                $screen = get_current_screen();

                foreach ( $this->args['contextual_help'] as $tab ) {
                    $screen->add_help_tab($tab);
                }

                if ( !empty($this->args['contextual_help_sidebar']) ) {
                    $screen->set_help_sidebar($this->args['contextual_help_sidebar']);
                }
            }
        }

        // option page html output
        public function add_options_html()
        {
            $has_nav       = ( count($this->tabs) > 1 ) ? true : false;
            $show_all      = ( !$has_nav ) ? ' ovic-show-all' : '';
            $ajax_class    = ( $this->args['ajax_save'] ) ? ' ovic-save-ajax' : '';
            $sticky_class  = ( $this->args['sticky_header'] ) ? ' ovic-sticky-header' : '';
            $wrapper_class = ( $this->args['framework_class'] ) ? ' ' . $this->args['framework_class'] : '';
            $theme         = ( $this->args['theme'] ) ? ' ovic-theme-' . $this->args['theme'] : '';
            $class         = ( $this->args['class'] ) ? ' ' . $this->args['class'] : '';

            do_action('ovic_options_before');

            echo '<div class="ovic ovic-options' . $theme . $class . $wrapper_class . '" data-slug="' . $this->args['menu_slug'] . '" data-unique="' . $this->unique . '">';

            echo '<div class="ovic-container">';

            echo '<form method="post" action="" enctype="multipart/form-data" id="ovic-form" autocomplete="off">';

            echo '<input type="hidden" class="ovic-section-id" name="ovic_transient[section]" value="1">';

            wp_nonce_field('ovic_options_nonce', 'ovic_options_nonce' . $this->unique);

            echo '<div class="ovic-header' . esc_attr($sticky_class) . '">';

            echo '<div class="ovic-header-inner">';

            echo '<div class="ovic-header-left">';
            echo '<h1>' . $this->args['framework_title'] . '</h1>';
            echo '</div>';

            echo '<div class="ovic-header-right">';

            $notice_class = ( !empty($this->notice) ) ? ' ovic-form-show' : '';
            $notice_text  = ( !empty($this->notice) ) ? $this->notice : '';

            echo '<div class="ovic-form-result ovic-form-success' . $notice_class . '">' . $notice_text . '</div>';

            echo ( $this->args['show_form_warning'] ) ? '<div class="ovic-form-result ovic-form-warning">' . esc_html__('Settings have changed, you should save them!',
                    'ovic-addon-toolkit') . '</div>' : '';

            echo ( $has_nav && $this->args['show_all_options'] ) ? '<div class="ovic-expand-all" title="' . esc_html__('show all options',
                    'ovic-addon-toolkit') . '"><i class="fa fa-outdent"></i></div>' : '';

            echo ( $this->args['show_search'] ) ? '<div class="ovic-search"><input type="text" name="ovic-search" placeholder="' . esc_html__('Search option(s)',
                    'ovic-addon-toolkit') . '" autocomplete="off" /></div>' : '';

            echo '<div class="ovic-buttons">';
            echo '<input type="submit" name="' . $this->unique . '[_nonce][save]" class="button button-primary ovic-top-save ovic-save' . $ajax_class . '" value="' . esc_html__('Save',
                    'ovic-addon-toolkit') . '" data-save="' . esc_html__('Saving...', 'ovic-addon-toolkit') . '">';
            echo ( $this->args['show_reset'] ) ? '<input type="submit" name="ovic_transient[reset_section]" class="button button-secondary ovic-reset-section ovic-confirm" value="' . esc_html__('Reset Section',
                    'ovic-addon-toolkit') . '"  data-confirm="' . esc_html__('Are you sure to reset this section options?',
                    'ovic-addon-toolkit') . '">' : '';
            echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="ovic_transient[reset]" class="button button-secondary ovic-warning-primary ovic-reset-all ovic-confirm" value="' . esc_html__('Reset All',
                    'ovic-addon-toolkit') . '" data-confirm="' . esc_html__('Are you sure to reset all options?',
                    'ovic-addon-toolkit') . '">' : '';
            echo '</div>';

            echo '</div>';

            echo '<div class="clear"></div>';
            echo '</div>';
            echo '</div>';

            echo '<div class="ovic-wrapper' . $show_all . '">';

            if ( $has_nav ) {
                echo '<div class="ovic-nav ovic-nav-options">';

                echo '<ul>';

                $tab_key = 1;

                foreach ( $this->tabs as $tab ) {
                    $tab_error = $this->error_check($tab);

                    $tab_icon = ( !empty($tab['icon']) ) ? '<i class="' . $tab['icon'] . '"></i>' : '';

                    if ( !empty($tab['sections']) ) {
                        echo '<li class="ovic-tab-depth-0">';

                        echo '<a href="#tab=' . $tab_key . '" class="ovic-arrow">' . $tab_icon . $tab['title'] . $tab_error . '</a>';

                        echo '<ul>';

                        foreach ( $tab['sections'] as $sub ) {
                            $sub_error = $this->error_check($sub);
                            $sub_icon  = ( !empty($sub['icon']) ) ? '<i class="' . $sub['icon'] . '"></i>' : '';

                            echo '<li class="ovic-tab-depth-1"><a id="ovic-tab-link-' . $tab_key . '" href="#tab=' . $tab_key . '">' . $sub_icon . $sub['title'] . $sub_error . '</a></li>';

                            $tab_key++;
                        }

                        echo '</ul>';

                        echo '</li>';
                    } else {
                        echo '<li class="ovic-tab-depth-0"><a id="ovic-tab-link-' . $tab_key . '" href="#tab=' . $tab_key . '">' . $tab_icon . $tab['title'] . $tab_error . '</a></li>';

                        $tab_key++;
                    }
                }

                echo '</ul>';

                echo '</div>';
            }

            echo '<div class="ovic-content">';

            echo '<div class="ovic-sections">';

            $section_key = 1;

            if ( !empty($this->sections) ) {
                foreach ( $this->sections as $section ) {
                    $onload       = ( !$has_nav ) ? ' ovic-onload' : '';
                    $section_icon = ( !empty($section['icon']) ) ? '<i class="ovic-icon ' . $section['icon'] . '"></i>' : '';

                    echo '<div id="ovic-section-' . $section_key . '" class="ovic-section' . $onload . '">';
                    echo ( $has_nav ) ? '<div class="ovic-section-title"><h3>' . $section_icon . $section['title'] . '</h3></div>' : '';
                    echo ( !empty($section['description']) ) ? '<div class="ovic-field ovic-section-description">' . $section['description'] . '</div>' : '';

                    if ( !empty($section['fields']) ) {
                        foreach ( $section['fields'] as $field ) {
                            $is_field_error = $this->error_check($field);

                            if ( !empty($is_field_error) ) {
                                $field['_error'] = $is_field_error;
                            }

                            $value = ( !empty($field['id']) && isset($this->save_options[$field['id']]) ) ? $this->save_options[$field['id']] : '';

                            echo OVIC::field($field, $value, $this->unique, 'options');
                        }
                    } else {
                        echo '<div class="ovic-no-option ovic-text-muted">' . esc_html__('No option provided by developer.',
                                'ovic-addon-toolkit') . '</div>';
                    }

                    echo '</div>';

                    $section_key++;
                }
            }

            echo '</div>';

            echo '<div class="clear"></div>';

            echo '</div>';

            echo '<div class="ovic-nav-background"></div>';

            echo '</div>';

            if ( !empty($this->args['show_footer']) ) {
                echo '<div class="ovic-footer">';

                echo '<div class="ovic-buttons">';
                echo '<input type="submit" name="ovic_transient[save]" class="button button-primary ovic-save' . $ajax_class . '" value="' . esc_html__('Save',
                        'ovic-addon-toolkit') . '" data-save="' . esc_html__('Saving...', 'ovic-addon-toolkit') . '">';
                echo ( $this->args['show_reset'] ) ? '<input type="submit" name="ovic_transient[reset_section]" class="button button-secondary ovic-reset-section ovic-confirm" value="' . esc_html__('Reset Section',
                        'ovic-addon-toolkit') . '" data-confirm="' . esc_html__('Are you sure to reset this section options?',
                        'ovic-addon-toolkit') . '">' : '';
                echo ( $this->args['show_reset_all'] ) ? '<input type="submit" name="ovic_transient[reset]" class="button button-secondary ovic-warning-primary ovic-reset-all ovic-confirm" value="' . esc_html__('Reset All',
                        'ovic-addon-toolkit') . '" data-confirm="' . esc_html__('Are you sure to reset all options?',
                        'ovic-addon-toolkit') . '">' : '';
                echo '</div>';

                if ( !empty($this->args['footer_text']) ) {
                    echo '<div class="ovic-copyright">' . $this->args['footer_text'] . '</div>';
                }

                echo '<div class="clear"></div>';
                echo '</div>';
            }

            echo '</form>';

            echo '</div>';

            echo '<div class="clear"></div>';

            echo ( !empty($this->args['footer_after']) ) ? $this->args['footer_after'] : '';

            echo '</div>';

            do_action('ovic_options_after');
        }
    }
}