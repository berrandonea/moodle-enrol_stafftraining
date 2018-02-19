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
 * Initially developped for :
 * Université de Cergy-Pontoise
 * 33, boulevard du Port
 * 95011 Cergy-Pontoise cedex
 * FRANCE
 *
 * Enrolment method for staff trainings, with hierarchical validation.
 *
 * @package    enrol_stafftraining
 * @copyright  Brice Errandonea <brice.errandonea@u-cergy.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * File : checkformdata.php
 * When a user asks for such an enrolment, he's sent to this page, to check the data he has just given.
 */

require_once('../../config.php');

// Check params.
$enroldataid = required_param('id', PARAM_INT);
$recordchief = optional_param('recordchief', 0, PARAM_INT);
$chiefid = optional_param('chiefid', 0, PARAM_INT);
$enroldata = $DB->get_record('enrol_stafftraining_enroldata', array('id' => $enroldataid), '*', MUST_EXIST);
$userdata = $DB->get_record('enrol_stafftraining_userdata', array('userid' => $enroldata->userid), '*', MUST_EXIST);
$enrol = $DB->get_record('enrol', array('id' => $enroldata->enrolid, 'enrol' => 'stafftraining'), '*', MUST_EXIST);
$course = $DB->get_record('course', array('id' => $enrol->courseid));
$coursecategory = $DB->get_record('course_categories', array('id' => $course->category));
$parentcategory = $DB->get_record('course_categories', array('id' => $coursecategory->parent));

$args = array('id' => $enroldataid);
$moodlefilename = '/enrol/stafftraining/checkformdata.php';
$PAGE->set_url($moodlefilename, $args);
$title = get_string('enrolto', 'enrol_stafftraining', $course->fullname);
$PAGE->set_title($title);
$PAGE->set_pagelayout('standard');
$PAGE->set_heading($title);
$trainingsurl = new moodle_url('/course/index.php');
$categoryurl = new moodle_url('/course/index.php', array('categoryid' => $coursecategory->id));
$parenturl = new moodle_url('/course/index.php', array('categoryid' => $parentcategory->id));
$PAGE->navbar->add(get_string('courses'), $trainingsurl);
$PAGE->navbar->add($parentcategory->name, $parenturl);
$PAGE->navbar->add($coursecategory->name, $categoryurl);
$PAGE->navbar->add($title);

require_login();

echo $OUTPUT->header();

if ($USER->id != $enroldata->userid) {
	echo html_writer::tag('h3', get_string('notyourequest', 'enrol_stafftraining'));
	echo $OUTPUT->footer();
	exit;
}

if ($enroldata->recorded) {
	echo html_writer::tag('h3', get_string('alreadyrecorded', 'enrol_stafftraining'));
	echo $OUTPUT->footer();
	exit;
}

if ($recordchief) {
	$confirmedchief = $DB->get_record('user', array('id' => $chiefid));
	if (!$confirmedchief) {
		$chiefid = 0;
	} else if (!checkemail($confirmedchief)) {
		$chiefid = 0;
	}
	$enroldata->chiefid = $chiefid;
	$enroldata->recorded = 1;
	$DB->update_record('enrol_stafftraining_enroldata', $enroldata);
	$userdata->chiefid = $chiefid;
	$userdata->chiefname = "$confirmedchief->firstname $confirmedchief->lastname";
	$DB->update_record('enrol_stafftraining_userdata', $userdata);
	echo html_writer::tag('h3', get_string('requestrecorded', 'enrol_stafftraining'));
	echo $OUTPUT->footer();
	exit;
}

$affectation = $DB->get_record('enrol_stafftraining_affectation', array('id' => $userdata->affectation));
if ($affectation->chiefid) {
	$affectationknownchief = $DB->get_record('user', array('id' => $affectation->chiefid));
	$affectation->knownchiefname = "$affectationknownchief->firstname $affectationknownchief->lastname";
}

if ($userdata->chiefname == $affectation->knownchiefname) {
	$enroldata->chiefid = $affectationknownchief->id;
	$enroldata->recorded = 1;
	$DB->update_record('enrol_stafftraining_enroldata', $enroldata);
	echo html_writer::tag('h3', get_string('requestrecorded', 'enrol_stafftraining'));
} else {
	$selectchiefoptions = array('0' => get_string('notinlist', 'enrol_stafftraining'));
	echo html_writer::tag('p', get_string('declaredchiefname', 'enrol_stafftraining', $userdata->chiefname));
	if ($affectation->knownchiefname) {
		echo html_writer::tag('p', get_string('affectationknownchief', 'enrol_stafftraining', $affectation));
		$selectchiefoptions[$affectation->chiefid] = $affectation->knownchiefname;
	}	
	$potentialchiefs = getpotentialchiefs($userdata);
	if ($potentialchiefs) {
		foreach ($potentialchiefs as $potentialchief) {
			$selectchiefoptions[$potentialchief->id] = "$potentialchief->firstname $potentialchief->lastname";
		}
	} else {
		echo html_writer::tag('p', get_string('nomatchingchief', 'enrol_stafftraining'));
	}
	echo html_writer::tag('p', get_string('selectresponsible', 'enrol_stafftraining'));
	echo '<ul>';
	foreach ($selectchiefoptions as $optionid => $optionname) {
		$potentialchiefurl = new moodle_url('/enrol/stafftraining/checkformdata.php', array('id' => $enroldataid, 'recordchief' => 1, 'chiefid' => $optionid));		
		echo html_writer::tag('li', html_writer::link($potentialchiefurl, $optionname));
	}
	echo '</ul>';
}
echo $OUTPUT->footer();

//~ https://seformer.u-cergy.fr/enrol/stafftraining/checkformdata.php?id=10
//~ $thislistname = required_param('name', PARAM_ALPHA);
//~ $courseid = required_param('course', PARAM_INT);
//~ if ($courseid == 1) {
    //~ header("Location: $CFG->wwwroot/index.php");
//~ }
//~ $elementname = optional_param('element', '', PARAM_TEXT);
//~ $edit = optional_param('edit', '', PARAM_ALPHA);
//~ $newvalue = optional_param('newvalue', '', PARAM_TEXT);
//~ $editing = optional_param('editing', 0, PARAM_INT);
//~ $course = get_course($courseid);
//~ require_login($course);
//~ $coursecontext = context_course::instance($courseid);
//~ require_capability('block/catalogue:viewlists', $coursecontext);

function getpotentialchiefs($userdata) {
	global $DB;
	$potentialchiefs = array();
	$declaredchiefnameparts = explode(' ', $userdata->chiefname);
	foreach ($declaredchiefnameparts as $declaredchiefnamepart) {
		$firstnamepotentials = $DB->get_records('user', array('firstname' => $declaredchiefnamepart));
		foreach ($firstnamepotentials as $firstnamepotential) {
			if (checkemail($firstnamepotential)) {
				$potentialchiefs[] = $firstnamepotential;
			}
		}
		$lastnamepotentials = $DB->get_records('user', array('lastname' => $declaredchiefnamepart));
		foreach ($lastnamepotentials as $lastnamepotential) {
			if (checkemail($lastnamepotential)) {
				$potentialchiefs[] = $lastnamepotential;
			}
		}
	}
	return $potentialchiefs;
}

function checkemail($user) {
	$emailparts = explode('@', $user->email);
	if (!isset($emailparts[1])) {
		return false;
	}
	if (isset($emailparts[2])) {
		return false;
	}
	if ($emailparts[1] == 'u-cergy.fr') {
		return true;
	}
	if ($emailparts[1] == 'iufm.u-cergy.fr') {
		return true;
	}
	return false;
}
