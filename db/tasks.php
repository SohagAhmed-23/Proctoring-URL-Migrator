<?php
defined('MOODLE_INTERNAL') || die();

$tasks = [
    [
        'classname' => 'local_proctoringmirationurl\task\migrate_urls',
        'blocking'  => 0,
        'minute'    => 2,
        'hour'      => '*',
        'day'       => '*',
        'dayofweek' => '*',
        'month'     => '*'
    ],
];
