<?php namespace Admin;

class Settings
{
    public $updated;
    /**
      * This method will be used to register
      * our custom settings admin page
      */

    public function init()
    {
        // register page
        // add_action('admin_menu', array($this, 'setupTabs'));
        add_action('network_admin_menu', array($this, 'setupTabs'));

        // update settings
        // add_action('admin_menu', array($this, 'update'));
        add_action('network_admin_menu', array($this, 'update'));
    }

    /**
      * This method will be used to register
      * our custom settings admin page
      */

    public function setupTabs()
    {
        add_submenu_page(
            'settings.php',
            __('The Plugin Settings', 'the-plugin-domain'),
            __('The Plugin'),
            'manage_options',
            'page-slug',
            array($this, 'screen')
        );

        return $this;
    }

    /**
      * This method will parse the contents of
      * our custom settings age
      */

    public function screen()
    {
        ?>

        <div class="wrap">

            <h2><?php _e('The Plugin Settings', 'the-plugin-domain'); ?></h2>

            <?php if ( $this->updated ) : ?>
                <div class="updated notice is-dismissible">
                    <p><?php _e('Settings updated successfully!', 'the-plugin-domain'); ?></p>
                </div>
            <?php endif; ?>

            <form method="post">

                <h3>Options</h3>
                <p>
                    <label>
                        <input type="checkbox" name="show_message" <?php
                          $checked = esc_attr($this->getSettings('show_message'));
                          if($checked){
                            ?> checked="checked"<?php
                          }
                        ?>> <?php _e('Show message on all sites', 'the-plugin-domain'); ?>
                    </label>
                </p>
                <h3>Message</h3>
                <?php
                  $message = stripslashes( $this->getSettings('message') );
                  $message = html_entity_decode( $message );

                  wp_editor( $message, 'message', array(
                    'teeny' => true,
                    'media_buttons' => false,
                    'textarea_name' => 'message'
                  ));
                ?>

                <?php wp_nonce_field('my_plugin_nonce', 'my_plugin_nonce'); ?>
                <?php submit_button(); ?>

            </form>

        </div>

        <?php
    }

    /**
      * Check for POST (form submission)
      * Verifies nonce first then calls
      * updateSettings method to update.
      */

    public function update()
    {
        if ( isset($_POST['submit']) ) {

            // verify authentication (nonce)
            if ( !isset( $_POST['my_plugin_nonce'] ) )
                return;

            // verify authentication (nonce)
            if ( !wp_verify_nonce($_POST['my_plugin_nonce'], 'my_plugin_nonce') )
                return;

            return $this->updateSettings();
        }
    }

    /**
      * Updates settings
      */

    public function updateSettings()
    {
        $settings = array();

        if ( isset($_POST['show_message']) ) {
            $settings['show_message'] = esc_attr($_POST['show_message']);
        }

        if ( isset($_POST['message']) ) {
            $settings['message'] = $_POST['message'];
        }

        if ( $settings ) {
            // update new settings
            update_site_option('the_plugin_settings', $settings);
        } else {
            // empty settings, revert back to default
            delete_site_option('the_plugin_settings');
        }

        $this->updated = true;
    }

    /**
      * Updates settings
      *
      * @param $setting string optional setting name
      */

    public function getSettings($setting='')
    {
        global $the_plugin_settings;

        if ( isset($the_plugin_settings) ) {
            if ( $setting ) {
                return isset($the_plugin_settings[$setting]) ? $the_plugin_settings[$setting] : null;
            }
            return $the_plugin_settings;
        }

        $the_plugin_settings = wp_parse_args(get_site_option('the_plugin_settings'), array(
            'message' => null,
            'show_message' => null,
            'show_message_nonpublic' => null
        ));

        if ( $setting ) {
            return isset($the_plugin_settings[$setting]) ? $the_plugin_settings[$setting] : null;
        }
        return $the_plugin_settings;
    }
}
