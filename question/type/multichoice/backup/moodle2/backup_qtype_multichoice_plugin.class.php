<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    moodlecore
 * @subpackage backup-moodle2
 * @copyright  2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Provides the information to backup multichoice questions
 */
class backup_qtype_multichoice_plugin extends backup_qtype_plugin {

    /**
     * Returns the qtype information to attach to question element
     */
    protected function define_question_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '../../qtype', 'multichoice');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // This qtype uses standard question_answers, add them here
        // to the tree before any other information that will use them
        $this->add_question_question_answers($pluginwrapper);

        // Now create the qtype own structures
        $multichoice = new backup_nested_element('multichoice', array('id'), array(
            'layout', 'answers', 'single', 'shuffleanswers',
            'correctfeedback', 'correctfeedbackformat', 'partiallycorrectfeedback', 'partiallycorrectfeedbackformat',
            'incorrectfeedback', 'incorrectfeedbackformat', 'answernumbering'));

        // Now the own qtype tree
        $pluginwrapper->add_child($multichoice);

        // set source to populate the data
        $multichoice->set_source_table('question_multichoice', array('question' => backup::VAR_PARENTID));

        // don't need to annotate ids nor files

        return $plugin;
    }

    /**
     * Returns one array with filearea => mappingname elements for the qtype
     *
     * Used by {@link get_components_and_fileareas} to know about all the qtype
     * files to be processed both in backup and restore.
     */
    public static function get_qtype_fileareas() {
        // TODO: Discuss. Commented below are the "in theory" correct
        // mappings for those fileareas. Instead we are using question for
        // them, that will cause problems in the future if we want to change
        // any of them to be 1..n (i.e. we should be always pointing to own id)
        return array(
            //'correctfeedback' => 'question_multichoice',
            //'partiallycorrectfeedback' => 'question_multichoice',
            //'incorrectfeedback' => 'question_multichoice');
            'correctfeedback' => 'question_created',
            'partiallycorrectfeedback' => 'question_created',
            'incorrectfeedback' => 'question_created');
    }
}
