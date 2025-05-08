<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
   
    $settings = new admin_settingpage(
        'local_proctoringmirationurl',
        get_string('pluginname', 'local_proctoringmirationurl'),
        'moodle/site:config'   
    );

    // Previous (search) URL.
    $settings->add(new admin_setting_configtext(
        'local_proctoringmirationurl/searchurl',
        get_string('searchurl', 'local_proctoringmirationurl'),
        get_string('searchurldesc', 'local_proctoringmirationurl'),
        'http://localhost/proctoring405',
        PARAM_URL
    ));

    // New (replace) URL.
    $settings->add(new admin_setting_configtext(
        'local_proctoringmirationurl/replaceurl',
        get_string('replaceurl', 'local_proctoringmirationurl'),
        get_string('replaceurldesc', 'local_proctoringmirationurl'),
        'http://localhost/sohag405new',
        PARAM_URL
    ));

   

    $ADMIN->add('localplugins', $settings);
}
