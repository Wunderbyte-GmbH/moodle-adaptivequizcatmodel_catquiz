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
 * Backup subplugin class for catquiz
 *
 * @package    catmodel_catquiz
 * @copyright  2024 Wunderbyte GmbH <info@wunderbyte.at>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_adaptivequizcatmodel_catquiz_subplugin extends backup_subplugin {
    /**
     * Define the subplugin structure
     * @param string $connectionpoint
     *
     * @return backup_subplugin_element
     */
    public function define_subplugin_structure($connectionpoint) {
        // Create XML elements.
        $subplugin = $this->get_subplugin_element();
        $subpluginwrapper = new backup_nested_element($this->get_recommended_name() . $connectionpoint);
        $subpluginelement = new backup_nested_element('catquiz_test', ['id'], [
            // List all your subplugin's database fields here.
            'parentid',
            'componentid',
            'component',
            'catscaleid',
            'courseid',
            'name',
            'description',
            'descriptionformat',
            'json',
        ]);

        // Build XML structure.
        $subplugin->add_child($subpluginwrapper);
        $subpluginwrapper->add_child($subpluginelement);

        // Set sourece using custom SQL to get only the relevant row.
        $sql = <<<SQL
            SELECT *
            FROM {local_catquiz_tests}
            WHERE component = :component
            AND componentid = :componentid
        SQL;

        $params = [
            'component' => ['sqlparam' => 'mod_adaptivequiz'],
            'componentid' => backup::VAR_PARENTID,
        ];
        $subpluginelement->set_source_sql($sql, $params);

        return $subplugin;
    }
}
