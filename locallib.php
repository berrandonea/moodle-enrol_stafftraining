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
 * File : locallib.php
 * Enrolment form class definition.
 */

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class enrol_stafftraining_enrol_form extends moodleform {
    protected $instance;
    protected $toomany = false;

    /**
     * Overriding this function to get unique form id for multiple self enrolments.
     *
     * @return string form identifier
     */
    protected function get_form_identifier() {
        $formid = $this->_customdata->id.'_'.get_class($this);
        return $formid;
    }

    public function definition() {
        global $USER, $OUTPUT, $CFG, $DB, $COURSE;

        $user     = $DB->get_record('user', array('id' => $USER->id));
        $userdata = $DB->get_record('enrol_stafftraining_userdata', array('userid' => $USER->id));

        $mform = $this->_form;
        $instance = $this->_customdata;
        $this->instance = $instance;
        $plugin = enrol_get_plugin('stafftraining');

        $heading = $plugin->get_instance_name($instance);
        $mform->addElement('header', 'stafftrainingheader', $heading);

		$emailparts = explode('@', $user->email);

		if (($user->auth == 'cas') && (($emailparts[1] == 'u-cergy.fr')||($emailparts[1] == 'iufm.u-cergy.fr'))) {
            $mform->addElement('html', '<br><br>');
            $mform->addElement('html', '<h3>'.get_string('aboutyou', 'enrol_stafftraining').'</h3>');

			$mform->addElement('text', 'phonenumber', get_string('phone'));
			$mform->setType('phonenumber', PARAM_TEXT);
			if ($user->phone1) {
				$defaultphone = $user->phone1;
			} else {
				$defaultphone = '';
			}
			$mform->setDefault('phonenumber', $defaultphone);
			$mform->addRule('phonenumber', get_string('required'), 'required', null);

			$mform->addElement('date_selector', 'birthday', get_string('birthday', 'enrol_stafftraining'));
			if ($userdata) {
				if ($userdata->birthday) {
					$mform->setDefault('birthday', $userdata->birthday);
				}
			}
			$mform->addRule('birthday', get_string('required'), 'required', null);

            $statusoptions = array(get_string('permanent', 'enrol_stafftraining'), get_string('contractual', 'enrol_stafftraining'));
			$mform->addElement('select', 'status', get_string('status'), $statusoptions);
			$mform->setType('status', PARAM_INT);
			$mform->setDefault('status', 0);
            if ($userdata) {
				if ($userdata->status) {
					$mform->setDefault('status', $userdata->status);
				}
			}
			$mform->addRule('status', get_string('required'), 'required', null);
			
			$corpsoptions = array('AENES', 'ITRF', get_string('defaultcourseteacher'), 'BU');
			$mform->addElement('select', 'corps', get_string('corps', 'enrol_stafftraining'), $corpsoptions);
			$mform->setType('corps', PARAM_INT);
            if ($userdata) {
				if ($userdata->corps) {
					$mform->setDefault('corps', $userdata->status);
				}
			}
			$mform->addRule('corps', get_string('required'), 'required', null);

			$mform->addElement('text', 'rank', get_string('rank', 'enrol_stafftraining'));
			$mform->setType('rank', PARAM_TEXT);
			if ($userdata) {
				if ($userdata->rank) {
					$mform->setDefault('rank', $userdata->rank);
				}
			}
			$mform->addRule('rank', get_string('required'), 'required', null);

			$affectoptions = array();
			$affectations = $DB->get_records('enrol_stafftraining_affectation', array());
			foreach ($affectations as $affectation) {
				$affectoptions[$affectation->id] = $affectation->name;
			}
			$mform->addElement('select', 'affectation', get_string('affectation', 'enrol_stafftraining'), $affectoptions);
			$mform->setType('affectation', PARAM_INT);
            if ($userdata) {
				if ($userdata->affectation) {
					$mform->setDefault('affectation', $userdata->affectation);
				}
			}
			$mform->addRule('affectation', get_string('required'), 'required', null);

			$mform->addElement('text', 'chiefname', get_string('chiefname', 'enrol_stafftraining'));
			$mform->setType('chiefname', PARAM_TEXT);
			if ($userdata) {
				if ($userdata->chiefid) {
				    $chiefuser = $DB->get_record('user', array('id' => $userdata->chiefid));
				    $mform->setDefault('chiefname', "$chiefuser->firstname $chiefuser->lastname");
			    }
			}
			$mform->addRule('chiefname', get_string('required'), 'required', null);

			$mform->addElement('date_selector', 'arrival', get_string('arrivaldate', 'enrol_stafftraining'));
			if ($userdata) {
				if ($userdata->arrival) {
					$mform->setDefault('arrival', $userdata->arrival);
				}
			}
			$mform->addRule('arrival', get_string('required'), 'required', null);
			
			$mform->addElement('html', '<br><br>');

            //~ $mform->addElement('static', 'wantedtraining', '', get_string('wantedtraining', 'enrol_stafftraining').' : '.$COURSE->fullname);
            $mform->addElement('html', '<h3>'.get_string('availablegroups', 'enrol_stafftraining').' '.$COURSE->fullname.'</h3>');

            $groupoptions = array();
			$groups = $DB->get_records('groups', array('courseid' => $COURSE->id));
			
			$now = time();
			$days = array(0 => 'sun', 1 => 'mon', 2 => 'tue', 3 => 'wed', 4 => 'thu', 5 => 'fri', 6 => 'sat');
			foreach ($groups as $group) {
				// Don't include groups whose training has begun and those that are full.
				$begun = 0;
				$full = 0;
				$totalduration = 0;

				$grouphtml = "<span style='font-weight:bold'>$group->name</span><br>";

				$groupsessions = $DB->get_records('attendance_sessions', array('groupid' => $group->id));
				if ($groupsessions) {
					$grouphtml .= ucwords(get_string('sessions', 'attendance'));
					$grouphtml .= '<ul>';
					foreach ($groupsessions as $groupsession) {
				 	    if ($groupsession->sessdate < $now) {
						    $begun = 1;
					    } else {
							$timestart = $groupsession->sessdate;
							$timestop  = $groupsession->sessdate + $groupsession->duration;
							$totalduration += $groupsession->duration;
							$daynum = date('w', $timestart);
							$dayidentifier = 'day'.$days[$daynum];
						    $grouphtml .= "<li>".get_string('on', 'enrol_stafftraining').' '.get_string($dayidentifier, 'enrol_stafftraining')
						                  .' '.date('d/m/Y', $timestart).', '
						                  .get_string('from', 'enrol_stafftraining').' '.date('H:i', $timestart).' '
						                  .get_string('to', 'enrol_stafftraining').' '.date('H:i', $timestop).".</li>";
					    }
				    }
					$grouphtml .= '</ul>';
					$grouphtml .= get_string('totalduration', 'enrol_stafftraining');
					$grouphtml .= " : $totalduration<br><br>";
				} else {
                    $grouphtml .= get_string('nodateyet', 'enrol_stafftraining');
                    $grouphtml .= '<br><br>';
				}

				//TODO : full => créer une capacité d'accueil pour les formations.
				
				
				if (!$begun && !$full) {					
					//~ $mform->addElement('static', 'group'.$group->id, $group->name, "Pas encore de date");
				    $mform->addElement('html', $grouphtml);				    
					$groupoptions[$group->id] = $group->name;
				}				
				
			}

			$mform->addElement('html', '<br><br>');
			$mform->addElement('html', '<h3>'.get_string('abouttraining', 'enrol_stafftraining').'</h3>');
			$mform->addElement('select', 'wantedgroup', get_string('wantedgroup', 'enrol_stafftraining'), $groupoptions);
			$mform->setType('wantedgroup', PARAM_INT);
            if ($userdata) {
				if ($userdata->wantedgroup) {
					$mform->setDefault('wantedgroup', $userdata->wantedgroup);
				}
			}
			$mform->addRule('wantedgroup', get_string('required'), 'required', null);

            $mform->addElement('advcheckbox', 'planned', 
                               get_string('planned', 'enrol_stafftraining'),
                               ' ', 
                               array('group' => 1), 
                               array(0, 1));

			$typeoptions = array();
			$types = $DB->get_records('enrol_stafftraining_type', array());
			foreach ($types as $type) {
				$typeoptions[$type->id] = $type->name;
			}
			$mform->addElement('select', 'type', get_string('trainingtype', 'enrol_stafftraining'), $typeoptions);
			$mform->setType('type', PARAM_INT);
			$mform->addRule('type', get_string('required'), 'required', null);

            $mform->addElement('textarea', 'interest', get_string('interest', 'enrol_stafftraining'), 'wrap="virtual" rows="5" cols="50"');
            $mform->setType('interest', PARAM_TEXT);
            $mform->addRule('interest', get_string('required'), 'required', null);

            $mform->addElement('textarea', 'schedule', get_string('schedule', 'enrol_stafftraining'), 'wrap="virtual" rows="5" cols="50"');
            $mform->setType('schedule', PARAM_TEXT);
            
            $mform->addElement('html', '<p style="font-size:12">'.get_string('pleasefillschedule', 'enrol_stafftraining').'</p><br><br>');
            

            $mform->addElement('textarea', 'accessibility', get_string('accessibility', 'enrol_stafftraining'), 'wrap="virtual" rows="5" cols="50"');
            $mform->setType('accessibility', PARAM_TEXT);

            $mform->addElement('textarea', 'infos', get_string('infos', 'enrol_stafftraining'), 'wrap="virtual" rows="5" cols="50"');
            $mform->setType('infos', PARAM_TEXT);

			//~ $mform->addElement('static', 'nokey', '', get_string('nopassword', 'enrol_stafftraining'));
            $this->add_action_buttons(false, get_string('enrolme', 'enrol_stafftraining'));

            $mform->addElement('hidden', 'id');
            $mform->setType('id', PARAM_INT);
            $mform->setDefault('id', $instance->courseid);

            $mform->addElement('hidden', 'instance');
            $mform->setType('instance', PARAM_INT);
            $mform->setDefault('instance', $instance->id);
		} else {
			$mform->addElement('static', 'staffrestricted', '', get_string('staffrestricted', 'enrol_stafftraining'));
		}

        //~ if ($instance->password) {
            //~ // Change the id of self enrolment key input as there can be multiple self enrolment methods.
            //~ $mform->addElement('password', 'enrolpassword', get_string('password', 'enrol_self'),
                    //~ array('id' => 'enrolpassword_'.$instance->id));
            //~ $context = context_course::instance($this->instance->courseid);
            //~ $keyholders = get_users_by_capability($context, 'enrol/self:holdkey', user_picture::fields('u'));
            //~ $keyholdercount = 0;
            //~ foreach ($keyholders as $keyholder) {
                //~ $keyholdercount++;
                //~ if ($keyholdercount === 1) {
                    //~ $mform->addElement('static', 'keyholder', '', get_string('keyholder', 'enrol_self'));
                //~ }
                //~ $keyholdercontext = context_user::instance($keyholder->id);
                //~ if ($USER->id == $keyholder->id || has_capability('moodle/user:viewdetails', context_system::instance()) ||
                        //~ has_coursecontact_role($keyholder->id)) {
                    //~ $profilelink = '<a href="' . $CFG->wwwroot . '/user/view.php?id=' . $keyholder->id . '&amp;course=' .
                    //~ $this->instance->courseid . '">' . fullname($keyholder) . '</a>';
                //~ } else {
                    //~ $profilelink = fullname($keyholder);
                //~ }
                //~ $profilepic = $OUTPUT->user_picture($keyholder, array('size' => 35, 'courseid' => $this->instance->courseid));
                //~ $mform->addElement('static', 'keyholder'.$keyholdercount, '', $profilepic . $profilelink);
            //~ }

        //~ } else {
            //~ $mform->addElement('static', 'nokey', '', get_string('nopassword', 'enrol_stafftraining'));
        //~ }
        
    }

    public function validation($data, $files) {
        global $DB, $CFG, $USER;
        
        /*
         * $data
         * 
         * Array (
               [phonenumber] => 0123456789
               [birthday] => 1511218800
               [submitbutton] => Inscrivez-moi
               [id] => 79
               [instance] => 280
               [sesskey] => K024WVbhQ8
               [_qf__280_enrol_stafftraining_enrol_form] => 1
           )
         * 
         */

        $errors = parent::validation($data, $files);
        $instance = $this->instance;

        if ($this->toomany) {
            $errors['notice'] = get_string('error');
            return $errors;
        }

        //~ if ($instance->password) {
            //~ if ($data['enrolpassword'] !== $instance->password) {
                //~ if ($instance->customint1) {
                    //~ // Check group enrolment key.
                    //~ if (!enrol_self_check_group_enrolment_key($instance->courseid, $data['enrolpassword'])) {
                        //~ // We can not hint because there are probably multiple passwords.
                        //~ $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_self');
                    //~ }

                //~ } else {
                    //~ $plugin = enrol_get_plugin('self');
                    //~ if ($plugin->get_config('showhint')) {
                        //~ $hint = core_text::substr($instance->password, 0, 1);
                        //~ $errors['enrolpassword'] = get_string('passwordinvalidhint', 'enrol_self', $hint);
                    //~ } else {
                        //~ $errors['enrolpassword'] = get_string('passwordinvalid', 'enrol_self');
                    //~ }
                //~ }
            //~ }
        //~ }

        $DB->set_field('user', 'phone1', $data['phonenumber'], array('id' => $USER->id));

        $userdata = $DB->get_record('enrol_stafftraining_userdata', array('userid' => $USER->id));
        $newuserdata = false;
        if (!$userdata) {
			$newuserdata = true;
			$userdata = new stdClass();
		}
		$userdata->userid = $USER->id;
        $userdata->birthday = $data['birthday'];
		$userdata->status = $data['status'];
		$userdata->corps = $data['corps'];
		$userdata->rank = $data['rank'];
		$userdata->affectation = $data['affectation'];
		$userdata->chiefname = $data['chiefname'];
		$userdata->arrival = $data['arrival'];
		if ($newuserdata) {
			$userdata->id = $DB->insert_record('enrol_stafftraining_userdata', $userdata);
		} else {
			$DB->update_record('enrol_stafftraining_userdata', $userdata);
		}

        $enroldata = new stdClass();
        $enroldata->enrolid = $instance->id;
        $enroldata->userid = $USER->id;
        $enroldata->groupid = $data['wantedgroup'];
        $enroldata->nodate = !$DB->record_exists('attendance_sessions', array('groupid' => $data['wantedgroup']));
        $enroldata->planned = $data['planned'];
        $enroldata->typeid = $data['type'];
        $enroldata->interest = $data['interest'];
        $enroldata->schedule = $data['schedule'];
        $enroldata->accessibility = $data['accessibility'];
        $enroldata->infos = $data['infos'];
        $enroldata->chieftext = '';
        $enroldata->organisertext = '';
        $enroldata->timeasked = time();
        $enroldata->id = $DB->insert_record('enrol_stafftraining_enroldata', $enroldata);

        $targeturl = new moodle_url("stafftraining/checkformdata.php", array('id' => $enroldata->id));
        redirect($targeturl);

        //~ return $errors;
    }
}
