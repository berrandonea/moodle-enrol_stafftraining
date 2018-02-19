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
 * File : lang/fr/enrol_stafftraining.php
 * 
 * French text strings
 */
 
$string['canntenrol'] = 'Il n\'est pas possible de s\'inscrire actuellement.';
$string['canntenrolearly'] = 'Vous ne pouvez pas encore vous inscrire ; les inscriptions ouvrent le {$a}.';
$string['canntenrollate'] = 'Vous ne pouvez plus vous inscrire ; les inscriptions sont closes depuis le {$a}.';
$string['cohortnonmemberinfo'] = 'Seuls les membres de la cohorte \'{$a}\' peuvent demander leur inscription par cette méthode.';
$string['cohortonly'] = 'Réservé aux membres de la cohorte';
$string['cohortonly_help'] = 'Vous pouvez réserver cette méthode d\'inscription aux membres d\'une certaine cohorte. Modifier ce réglage n\'a aucun effet sur les inscriptions déjà réalisées.';
$string['customwelcomemessage'] = 'Message de bienvenue personnalisé';
$string['customwelcomemessage_help'] = 'Vous pouvez ajouter un message de bienvenue personnalisé, sous forme de texte brut ou au format Moodle-auto.

Les variables suivantes peuvent être utilisées dans le message :

* Titre du cours ou de la formation {$a->coursename}
* Lien vers la page du profil de l\'utilisateur {$a->profileurl}
* Adresse e-mail de l\'utilisateur {$a->email}
* Nom complet de l\'utilisateur {$a->fullname}';

$string['defaultrole'] = 'Rôle attribué par défaut';
$string['defaultrole_desc'] = 'Choisissez quel rôle sera attribué aux utilisateurs inscrits par cette méthode.';
$string['enrolenddate'] = 'Date de fin';
$string['enrolenddate_help'] = 'Si activé, les utilisateurs ne pourront plus être inscrits après cette date.';
$string['enrolenddaterror'] = 'La date de fin ne peut pas pas précéder la date de début.';
$string['enrolme'] = 'Inscrivez-moi';
$string['enrolperiod'] = 'Durée de l\'inscription';
$string['enrolperiod_desc'] = 'Durée par défaut pendant laquelle l\'inscription est valide. Si mis à zéro, la durée d\'inscription sera illimitée par défaut.';
$string['enrolperiod_help'] = 'Durée pendant laquelle une inscription est valide, à partir de la date d\'inscription. Si désactivé, la durée d\'inscription sera illimitée.';
$string['enrolstartdate'] = 'Date de début';
$string['enrolstartdate_help'] = 'Si activé, les utilisateurs ne pourront pas solliciter leur inscription avant cette date.';
$string['expiredaction'] = 'Action lors de l\'expiration';
$string['expiredaction_help'] = 'Choisissez une action à réaliser quand l\'inscription d\'un utilisateur expire. Attention : en cas de désincription, les données de l\'utilisateur dans le cours seront perdues.';
$string['expirymessageenrollersubject'] = 'Notification de fin d\'inscription de type Formation interne.';
$string['expirymessageenrollerbody'] = 'L\'inscription à la formation interne \'{$a->course}\' va expirer dans un délai de {$a->threshold} pour les utilisateurs suivants :

{$a->users}

Pour prolonger leur inscription, visitez la page {$a->extendurl}';
$string['expirymessageenrolledsubject'] = 'Notification de fin d\'inscription à une formation interne';
$string['expirymessageenrolledbody'] = 'Bonjour {$a->user},

Ceci est une notification vous avertissant que votre inscription à la formation interne \'{$a->course}\' va expirer le {$a->timeend}.

Si vous avez besoin d\'aide, merci de contacter {$a->enroller}.';

$string['groupkey'] = 'Use group enrolment keys';
$string['groupkey_desc'] = 'Use group enrolment keys by default.';
$string['groupkey_help'] = 'In addition to restricting access to the course to only those who know the key, use of group enrolment keys means users are automatically added to groups when they enrol in the course.

Note: An enrolment key for the course must be specified in the stafftraining enrolment settings as well as group enrolment keys in the group settings.';
$string['keyholder'] = 'You should have received this enrolment key from:';
$string['longtimenosee'] = 'Désincrire automatiquement les utilisateurs inactifs après';
$string['longtimenosee_help'] = 'Si des utilisateurs n\'ont pas accédé à la formation depuis longtemps, ils sont automatiquement désinscrit. Ce paramètre détermine au bout de combien de temps.';
$string['maxenrolled'] = 'Capacité d\'accueil';
$string['maxenrolled_help'] = 'Spécifie le nombre maximum d\'utilisateurs qui peuvent être inscrits à la formation interne. 0 signifie pas de limite.';
$string['maxenrolledreached'] = 'La capacité d\'accueil maximale de cette formation interne est atteinte.';
$string['messageprovider:expiry_notification'] = 'Notifications d\'expiration d\'inscriptions de type Formation interne';
$string['newenrols'] = 'Autoriser les nouvelles inscriptions';
$string['newenrols_desc'] = 'Autoriser par défaut les utilisateurs à solliciter une inscription de type Formation interne pour chaque nouveau cours.';
$string['newenrols_help'] = 'Si Non, alors il n\'est plus possible de s\'inscrire par cette méthode mais les inscriptions déjà réalisées restent valables.';
$string['nopassword'] = 'Aucune validation n\'est nécessaire.';
$string['password'] = 'Clé d\'inscription';
$string['password_help'] = 'Une clé d\'inscription permet de restreindre l\'accès à un cours à ceux qui la connaissent.';
$string['passwordinvalid'] = 'Incorrect enrolment key, please try again';
$string['passwordinvalidhint'] = 'That enrolment key was incorrect, please try again<br />
(Here\'s a hint - it starts with \'{$a}\')';
$string['pluginname'] = 'Formation du personnel';
$string['pluginname_desc'] = 'Le plugin d\'inscription à une formation du personnel permet aux utilisateurs d\'indiquer quelle formation ils souhaitent suivre. Leur demande est soumise, d\'une part, à leur hiérarchie, et d\'autre part au service responsable de la formation du personnel.';
$string['requirepassword'] = 'Requiert une clé d\'inscription';
$string['requirepassword_desc'] = 'Require enrolment key in new courses and prevent removing of enrolment key from existing courses.';
$string['role'] = 'Rôle attribué par défaut.';
$string['stafftraining:config'] = 'Configurer les instances de méthodes d\inscription de type Formation du personnel';
$string['stafftraining:holdkey'] = 'Appear as the stafftraining enrolment key holder';
$string['stafftraining:manage'] = 'Gérer les utilisateurs inscrits via la méthode \"Formation du personnel\"';
$string['stafftraining:unenrol'] = 'Désinscrire des utilisateurs de la formation';
$string['stafftraining:unenrolself'] = 'Se désinscrire de la formation';
$string['sendcoursewelcomemessage'] = 'Envoyer un message de bienvenue dans la formation de la part';
$string['sendcoursewelcomemessage_help'] = 'Quand un utilisateur est inscrit à cette formation du personnel, un message de bienvenue peut lui être envoyé.';
$string['showhint'] = 'Montrer un indice';
$string['showhint_desc'] = 'Show first letter of the guest access key.';
$string['status'] = 'Allow existing enrolments';
$string['status_desc'] = 'Enable self enrolment method in new courses.';
$string['status_help'] = 'If enabled together with \'Allow new enrolments\' disabled, only users who were enrolled previously via stafftraining method can access the course. If disabled, this stafftraining enrolment method is effectively disabled, since all existing stafftraining enrolments are suspended and new users cannot ask for stafftraining enrol.';
$string['unenrol'] = 'Désinscrire un utilisateur';
$string['unenrolselfconfirm'] = 'Voulez-vous vraiment vous désinscrire de "{$a}"?';
$string['unenroluser'] = 'Voulez-vous vraiment désinscrire "{$a->user}" de "{$a->course}"?';
$string['usepasswordpolicy'] = 'Use password policy';
$string['usepasswordpolicy_desc'] = 'Use standard password policy for enrolment keys.';
$string['welcometocourse'] = 'Bienvenue dans la formation {$a}';
$string['welcometocoursetext'] = 'Bienvenue dans la formation {$a->coursename}!

Si vous ne l\'avez pas encore fait, vous devriez remplir votre page de profil pour que nous vous connaissions mieux :

  {$a->profileurl}';
  
$string['staffrestricted'] = 'Cette formation est réservée au personnel de l\'UCP. Si vous en faites partie, merci de vous connecter avec votre compte UCP.';
$string['birthday'] = 'Date de naissance';
$string['permanent'] = 'Titulaire';
$string['contractual'] = 'Contractuel';
$string['rank'] = 'Grade';
$string['affectation'] = 'Composante ou service';
$string['chiefname'] = 'Prénom puis nom de votre chef de service ou de votre RAC';
$string['arrivaldate'] = 'Date d\'entrée en poste';
$string['corps'] = 'Corps';
$string['wantedtraining'] = 'Formation souhaitée';
$string['wantedgroup'] = 'Groupe souhaité';
$string['planned'] = 'Formation prévue lors de l\'entretien professionnel ?';
$string['trainingtype'] = 'Type de formation';
$string['interest'] = 'Intérêt professionnel pour la formation';
$string['infos'] = 'Informations complémentaires';
$string['schedule'] = 'Avez-vous des contraintes d\'emploi du temps ?';
$string['accessibility'] = 'Merci d\'indiquer vos éventuels besoins d\'adaptation pour la formation (accessibilité, mobilité)';

$string['enrolto'] = 'Inscription à la formation {$a}';

$string['capacity'] = 'Capacité d\'accueil de chaque groupe';
$string['remainingplaces'] = 'Places restantes';
$string['availablegroups'] = 'Groupes disponibles pour la formation';
$string['nodateyet'] = 'Pas encore de date prévue';
$string['pleasefillschedule'] = 'Même si vous avez choisi, ci-dessus, un groupe pour lequel les dates de formation sont déjà fixées, merci de remplir ceci au cas où nous devions vous proposer d\'autres dates.';
$string['aboutyou'] = 'Vérifiez ou complétez vos informations personnelles';
$string['abouttraining'] = 'A propos de cette formation';
$string['on'] = 'Le';
$string['from'] = 'de';
$string['to'] = 'à';

$string['daysun'] = 'dimanche';
$string['daymon'] = 'lundi';
$string['daytue'] = 'mardi';
$string['daywed'] = 'mercredi';
$string['daythu'] = 'jeudi';
$string['dayfri'] = 'vendredi';
$string['daysat'] = 'samedi';

$string['declaredchiefname'] = 'Vous avez déclaré {$a} comme étant votre chef de service ou le RAC de votre composante.';
$string['affectationknownchief'] = 'D\'après nos informations, le responsable de {$a->name} est {$a->knownchiefname}.';
$string['notinlist'] = 'Il ou elle n\'est pas dans cette liste.';
$string['selectresponsible'] = 'Laquelle de ces personnes est votre chef de service ou votre RAC ?';
$string['nomatchingchief'] = 'Nous ne trouvons aucun nom correspondant à celui que vous avez donné.';
$string['notyourrequest'] = 'Cette demande n\'est pas la vôtre.';
$string['alreadyrecorded'] = 'Cette demande a déjà été enregistrée.';
$string['requestrecorded'] = 'Votre demande a bien été enregistrée. Elle va être examinée par votre responsable et par les organisateurs de la formation.';
