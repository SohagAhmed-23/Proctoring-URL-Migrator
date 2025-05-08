<?php
namespace local_proctoringmirationurl\task;

use core\task\scheduled_task;
defined('MOODLE_INTERNAL') || die();

class migrate_urls extends scheduled_task {

    /**
     * Return the name of the task as shown in admin UI
     *
     * @return string
     */
    public function get_name() {
        return get_string('taskname', 'local_proctoringmirationurl');
    }

    /**
     * Execute scheduled task: process URL migration in batches of 20
     */
    public function execute() {
        global $DB;

        $batchsize = 20;
        $search  = get_config('local_proctoringmirationurl', 'searchurl');
        $replace = get_config('local_proctoringmirationurl', 'replaceurl');

        // Tables and fields to update
        $tasks = [
            ['table' => 'quizaccess_proctoring_logs', 'field' => 'webcampicture'],
            ['table' => 'proctoring_face_images',       'field' => 'faceimage'],
            ['table' => 'proctoring_facematch_task',    'field' => 'refimageurl'],
            ['table' => 'proctoring_facematch_task',    'field' => 'targetimageurl'],
        ];

        foreach ($tasks as $task) {
            try {
                // Fetch up to 20 records needing update
                $records = $DB->get_records_select(
                    $task['table'],
                    "{$task['field']} LIKE ?",
                    ["%{$search}%"],
                    'id ASC',
                    '*',
                    0,
                    $batchsize
                );

                foreach ($records as $record) {
                    $oldurl = $record->{$task['field']};
                    $newurl = str_replace($search, $replace, $oldurl);

                    if ($oldurl === $newurl) {
                        continue;
                    }

                    // Perform the update directly
                    $record->{$task['field']} = $newurl;
                    $DB->update_record($task['table'], $record);
                    mtrace("Updated {$task['table']} ID {$record->id}: $oldurl → $newurl");
                }
            } catch (\dml_exception $e) {
                mtrace("Error updating {$task['table']}: " . $e->getMessage());
            }
        }
    }
}
