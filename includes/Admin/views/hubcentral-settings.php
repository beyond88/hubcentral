<?php
settings_errors();
$setting_options = wp_parse_args(get_option($this->_optionName), $this->_defaultOptions);
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
                        <div class="hubcentral-settings-tab active">
                            <div class="hubcentral-settings-section hubcentral-general_settings">
                                <table>
                                    <tbody>
                                        <tr data-id="button_label" id="hubcentral-meta-button_label" class="hubcentral-field hubcentral-meta-text type-text ">
                                            <th class="hubcentral-label">
                                                <label for="button_label">
                                                    Consumer Key
                                                </label>
                                            </th>
                                            <td class="hubcentral-control">
                                                <div class="hubcentral-control-wrapper">
                                                    <input class="hubcentral-settings-field" type="text" name="hubcentral_settings[consumer_key]" value="Consumer Key" placeholder="Set button label">
                                                    <p class="hubcentral-field-help"><strong>Note:</strong> Set WooCommerce consumer key</p>
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