<?php
settings_errors();
$setting_options = wp_parse_args(get_option($this->_optionName), $this->_defaultOptions);
// echo "<pre>";
// print_r($setting_options);
// echo "</pre>";
?>

<div class="hubcentral-settings-wrap">

    <div class="hubcentral-settings-header">
        <div class="hubcentral-header-full">
            <h2 class="title">HubCentral Settings</h2>
        </div>
    </div>
    <div class="hubcentral-left-right-settings">
        <div class="hubcentral-settings">
            <div class="hubcentral-settings-content">
                <div class="hubcentral-settings-form-wrapper">
                    <form method="post" id="hubcentral-settings-form" action="options.php" novalidate="novalidate">
                        <?php settings_fields($this->_optionGroup); ?>
                        <div class="hubcentral-settings-tab active">
                            <div class="hubcentral-settings-section hubcentral-general_settings">
                                <table>
                                    <tbody>
                                        <tr data-id="base_url" id="hubcentral-meta-base_url" class="hubcentral-field hubcentral-meta-text type-text ">
                                            <th class="hubcentral-label">
                                                <label for="base_url">
                                                    Base URL
                                                </label>
                                            </th>
                                            <td class="hubcentral-control">
                                                <div class="hubcentral-control-wrapper">
                                                    <input class="hubcentral-settings-field" type="text" name="hubcentral_settings[base_url]" value="<?php echo isset($setting_options['base_url']) ? esc_attr($setting_options['base_url']) : ''; ?>" placeholder="Base URL">
                                                    <p class="hubcentral-field-help"><strong>Note:</strong> Set WooCommerce endpoint URL</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr data-id="consumer_key" id="hubcentral-meta-consumer_key" class="hubcentral-field hubcentral-meta-text type-text ">
                                            <th class="hubcentral-label">
                                                <label for="consumer_key">
                                                    Consumer Key
                                                </label>
                                            </th>
                                            <td class="hubcentral-control">
                                                <div class="hubcentral-control-wrapper">
                                                    <input class="hubcentral-settings-field" type="text" name="hubcentral_settings[consumer_key]" value="<?php echo isset($setting_options['consumer_key']) ? esc_attr($setting_options['consumer_key']) : ''; ?>" placeholder="Consumer Key">
                                                    <p class="hubcentral-field-help"><strong>Note:</strong> Set WooCommerce consumer key</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr data-id="consumer_secret" id="hubcentral-meta-consumer_secret" class="hubcentral-field hubcentral-meta-text type-text ">
                                            <th class="hubcentral-label">
                                                <label for="consumer_secret">
                                                    Consumer Secret
                                                </label>
                                            </th>
                                            <td class="hubcentral-control">
                                                <div class="hubcentral-control-wrapper">
                                                    <input class="hubcentral-settings-field" type="text" name="hubcentral_settings[consumer_secret]" value="<?php echo isset($setting_options['consumer_secret']) ? esc_attr($setting_options['consumer_secret']) : ''; ?>" placeholder="Consumer Secret">
                                                    <p class="hubcentral-field-help"><strong>Note:</strong> Set WooCommerce consumer secret</p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php do_settings_fields($this->_optionGroup, 'default'); ?>
                        <?php do_settings_sections($this->_optionGroup, 'default'); ?>
                        <?php submit_button('Save Settings', 'hubcentral-settings-button'); ?>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>