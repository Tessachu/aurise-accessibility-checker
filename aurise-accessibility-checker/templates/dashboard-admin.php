<div class="wrap au-plugin">
    <h1><img src="<?php echo (esc_url($args['plugin_settings']['url'])); ?>assets/images/admin-logo.png" alt="<?php esc_html_e($args['plugin_settings']['name'] . ' ' . __('by AuRise Creative', 'aurise-accessibility-checker')); ?>" width="293" height="60" /></h1>
    <div class="au-plugin-admin-ui">
        <div class="loading-spinner"><img src="<?php echo (esc_url($args['plugin_settings']['url'])); ?>assets/images/progress.gif" alt="" width="32" height="32" /></div>
        <div class="admin-ui hide">
            <h2 class="nav-tab-wrapper hide">
                <a class="nav-tab" id="open-settings" href="#settings"><?php esc_html_e('Settings', 'aurise-accessibility-checker') ?></a>
            </h2>
            <div id="tab-content" class="container">
                <section id="settings" class="tab">
                    <?php
                    foreach ($args['plugin_settings']['options'] as $option_group_name => $group) {
                        $option_group = $args['plugin_settings']['prefix'] . $option_group_name; //This should match the group name used in register_setting()
                        echo ('<form method="post" action="options.php">');
                        settings_fields($option_group);
                        printf('<fieldset class="%s"><h2>%s</h2>', esc_attr($option_group_name), esc_html($group['title']));
                        echo ('<table class="form-table" role="presentation">');
                        do_settings_fields($args['plugin_settings']['slug'], $option_group);
                        echo ('</table></fieldset>');
                        submit_button(__('Save Settings', 'aurise-accessibility-checker'));
                        echo ('</form>');
                    }
                    if ($args['debug_mode']) {
                        echo ('<aside>');
                        if (defined('WP_DEBUG')) {
                            if (WP_DEBUG) {
                                printf('The <code>WP_DEBUG</code> constant is set to true, the allowed user roles <strong><em>will see the widget</em></strong> on the frontend.', 'aurise-accessibility-checker');
                            } else {
                                printf('The <code>WP_DEBUG</code> constant is not set to true, the allowed user roles <strong><em>will not see the widget</em></strong> on the frontend', 'aurise-accessibility-checker');
                            }
                        } else {
                            printf('The <code>WP_DEBUG</code> constant is not defined, the allowed user roles <strong><em>will not see the widget</em></strong> on the frontend', 'aurise-accessibility-checker');
                        }
                        echo ('</aside>');
                    } ?>
                </section>
            </div>
        </div>
    </div>
    <?php load_template($args['plugin_settings']['path'] . 'templates/dashboard-support.php', true, $args['plugin_settings']); ?>
</div>