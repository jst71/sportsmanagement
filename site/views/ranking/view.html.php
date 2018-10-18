<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage ranking
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\HTML\HTMLHelper;

jimport('joomla.filesystem.file');

/**
 * sportsmanagementViewRanking
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class sportsmanagementViewRanking extends sportsmanagementView {

    /**
     * sportsmanagementViewRanking::init()
     * 
     * @return void
     */
    function init() {

        $this->document->addScript(Uri::root(true) . '/components/' . $this->option . '/assets/js/smsportsmanagement.js');
        $this->document->addStyleSheet(Uri::base().'components/'.$this->option.'/assets/css/modalwithoutjs.css');
        
        sportsmanagementModelProject::setProjectID($this->jinput->getInt('p', 0), sportsmanagementModelProject::$cfg_which_database);
        $mdlDivisions = BaseDatabaseModel::getInstance("Divisions", "sportsmanagementModel");
        $mdlProjectteams = BaseDatabaseModel::getInstance("Projectteams", "sportsmanagementModel");
        $mdlTeams = BaseDatabaseModel::getInstance("Teams", "sportsmanagementModel");
        $model = $this->getModel();
        $this->paramconfig = sportsmanagementModelRanking::$paramconfig;
        $this->paramconfig['p'] = $this->project->slug;

        $rounds = sportsmanagementHelper::getRoundsOptions($this->project->id, 'ASC', true, NULL, sportsmanagementModelProject::$cfg_which_database);

        sportsmanagementModelProject::setProjectId($this->project->id, sportsmanagementModelProject::$cfg_which_database);

        $this->projectinfo = $this->project->projectinfo;
        $extended = sportsmanagementHelper::getExtended($this->project->extended, 'project');
        $this->extended = $extended;

        $this->overallconfig = sportsmanagementModelProject::getOverallConfig(sportsmanagementModelProject::$cfg_which_database);
        $this->tableconfig = $this->config;

        /**
         * sollen die vereinskürzel ersetzt und/oder angezeigt werden ?
         */
        if ($this->config['show_club_short_names'] || $this->config['show_replace_club_short_names']) {
            $mdlClubnames = BaseDatabaseModel::getInstance("clubnames", "sportsmanagementModel");
        }

        if (($this->overallconfig['show_project_rss_feed']) == 1) {
            $mod_name = "mod_jw_srfr";
            $rssfeeditems = '';
            $rssfeedlink = $this->extended->getValue('COM_SPORTSMANAGEMENT_PROJECT_RSS_FEED');
            if ($rssfeedlink) {
                $this->rssfeeditems = $model->getRssFeeds($rssfeedlink, $this->overallconfig['rssitems']);
            } else {
                $this->rssfeeditems = $rssfeeditems;
            }
        }

        if ($this->config['show_half_of_season']) {
            if ($this->config['show_table_4']) {
                sportsmanagementModelRanking::$part = 1;
                sportsmanagementModelRanking::$from = 0;
                sportsmanagementModelRanking::$to = 0;
                sportsmanagementModelRanking::computeRanking(sportsmanagementModelProject::$cfg_which_database);
                $this->firstRank = sportsmanagementModelRanking::$currentRanking;
            }

            if ($this->config['show_table_5']) {
                sportsmanagementModelRanking::$part = 2;
                sportsmanagementModelRanking::$from = 0;
                sportsmanagementModelRanking::$to = 0;
                sportsmanagementModelRanking::computeRanking(sportsmanagementModelProject::$cfg_which_database);
                $this->secondRank = sportsmanagementModelRanking::$currentRanking;
            }
            sportsmanagementModelRanking::$part = 0;
        }

        sportsmanagementModelRanking::computeRanking(sportsmanagementModelProject::$cfg_which_database);

        $this->round = sportsmanagementModelRanking::$round;
        $this->part = sportsmanagementModelRanking::$part;
        $this->rounds = $rounds;
        $this->divisions = sportsmanagementModelProject::getDivisions(0, sportsmanagementModelProject::$cfg_which_database);
        $this->type = sportsmanagementModelRanking::$type;
        $this->from = sportsmanagementModelProject::$_round_from;
        $this->to = sportsmanagementModelProject::$_round_to;
        $this->divLevel = sportsmanagementModelRanking::$divLevel;

        if ($this->config['show_table_1']) {
            $this->currentRanking = sportsmanagementModelRanking::$currentRanking;
        }

        $this->previousRanking = sportsmanagementModelRanking::$previousRanking;

        if ($this->config['show_table_2']) {
            $this->homeRank = sportsmanagementModelRanking::$homeRank;
        }

        if ($this->config['show_table_3']) {
            $this->awayRank = sportsmanagementModelRanking::$awayRank;
        }

        /**
         * wenn keine reiter ausgewählt wurden, dann nur die standardtabelle übergeben 
         */
        if (!$this->config['show_table_1'] ||
                !$this->config['show_table_2'] ||
                !$this->config['show_table_3'] ||
                !$this->config['show_table_4'] ||
                !$this->config['show_table_5']) {
            $this->currentRanking = sportsmanagementModelRanking::$currentRanking;
        }

        $this->current_round = sportsmanagementModelProject::getCurrentRound(__METHOD__ . ' ' . $this->jinput->getVar("view"), sportsmanagementModelProject::$cfg_which_database);

        // mannschaften holen
        $this->teams = sportsmanagementModelProject::getTeamsIndexedByPtid(0, 'name', sportsmanagementModelProject::$cfg_which_database, __METHOD__);

        $no_ranking_reason = '';
        $ranking_reason = array();
        if ($this->config['show_notes']) {
            foreach ($this->teams as $teams) {
                if ($teams->start_points) {
                    if ($teams->start_points < 0) {
                        $color = "red";
                    } else {
                        $color = "green";
                    }
                    $ranking_reason[$teams->name] = '<font color="' . $color . '">' . $teams->name . ': ' . $teams->start_points . ' Punkte Grund: ' . $teams->reason . '</font>';
                }
            }
        }

        if (sizeof($ranking_reason) > 0) {
            $this->ranking_notes = implode(", ", $ranking_reason);
        } else {
            $this->ranking_notes = $no_ranking_reason;
        }

        $this->previousgames = sportsmanagementModelRanking::getPreviousGames(sportsmanagementModelProject::$cfg_which_database);
        $frommatchday = array();
        $tomatchday = array();
        $lists = array();

        $frommatchday[] = HTMLHelper::_('select.option', '0', Text :: _('COM_SPORTSMANAGEMENT_RANKING_FROM_MATCHDAY'));
        $frommatchday = array_merge($frommatchday, $rounds);
        $lists['frommatchday'] = $frommatchday;
        $tomatchday[] = HTMLHelper::_('select.option', '0', Text :: _('COM_SPORTSMANAGEMENT_RANKING_TO_MATCHDAY'));
        $tomatchday = array_merge($tomatchday, $rounds);
        $lists['tomatchday'] = $tomatchday;

        $opp_arr = array();
        $opp_arr[] = HTMLHelper::_('select.option', "0", Text :: _('COM_SPORTSMANAGEMENT_RANKING_FULL_RANKING'));
        $opp_arr[] = HTMLHelper::_('select.option', "1", Text :: _('COM_SPORTSMANAGEMENT_RANKING_HOME_RANKING'));
        $opp_arr[] = HTMLHelper::_('select.option', "2", Text :: _('COM_SPORTSMANAGEMENT_RANKING_AWAY_RANKING'));

        $lists['type'] = $opp_arr;
        $this->lists = $lists;

        if (!isset($this->config['colors'])) {
            $this->config['colors'] = "";
        }

        $this->colors = sportsmanagementModelProject::getColors($this->config['colors'], sportsmanagementModelProject::$cfg_which_database);
        /**
         * wir holen uns alle mannschaften die dem projekt zugeordnet wurden
         */
        $this->allteams = $mdlProjectteams->getAllProjectTeams($this->project->id, 0, NULL, sportsmanagementModelProject::$cfg_which_database);

        /**
         * möchte der anwender die vereinskürzel ausgeschrieben sehen ?
         */
        if ($this->config['show_replace_club_short_names']) {
            /**
             * als erstes holen wir uns die vereinskürzel des landes im projekt
             */
            $short_names = $mdlClubnames->getClubNames($this->project->country);
        }

        if ($this->config['show_ranking_maps']) {

/**
 * leaflet benutzen
 */            
if ( $this->config['use_which_map'] )
{
$this->mapconfig = sportsmanagementModelProject::getTemplateConfig('map',$this->jinput->getInt('cfg_which_database',0));    
}
    
            $this->geo = new JSMsimpleGMapGeocoder();
            $this->geo->genkml3($this->project->id, $this->allteams);

            foreach ($this->allteams as $row) {
                $address_parts = array();
                if (!empty($row->club_address)) {
                    $address_parts[] = $row->club_address;
                }
                if (!empty($row->club_state)) {
                    $address_parts[] = $row->club_state;
                }
                if (!empty($row->club_location)) {
                    if (!empty($row->club_zipcode)) {
                        $address_parts[] = $row->club_zipcode . ' ' . $row->club_location;
                    } else {
                        $address_parts[] = $row->club_location;
                    }
                }
                if (!empty($row->club_country)) {
                    $address_parts[] = JSMCountries::getShortCountryName($row->club_country);
                }
                $row->address_string = implode(', ', $address_parts);
            }
        }

        unset($frommatchday);
        unset($tomatchday);
        unset($lists);

        // Set page title
        $pageTitle = Text::_('COM_SPORTSMANAGEMENT_RANKING_PAGE_TITLE');
        if (isset($this->project->name)) {
            $pageTitle .= ': ' . $this->project->name;
        }
        $this->document->setTitle($pageTitle);
        $this->headertitle = Text::_('COM_SPORTSMANAGEMENT_RANKING_PAGE_TITLE');

        if (!isset($this->config['table_class'])) {
            $this->config['table_class'] = 'table';
        }
        if (!isset($this->config['show_result_tabs'])) {
            $this->config['show_result_tabs'] = 'no_tabs';
        }
    }

}

?>
