<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage leaguechampionoverview
 * @file       view.html.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;

/**
 * sportsmanagementViewleaguechampionoverview
 * 
 * @package 
 * @author Dieter Plöger
 * @copyright 2020
 * @version $Id$
 * @access public
 */
class sportsmanagementViewleaguechampionoverview extends sportsmanagementView
{
	
	/**
	 * sportsmanagementViewleaguechampionoverview::init()
	 * 
	 * @return void
	 */
	function init()
	{
	   $this->leaguechampions = array();
       $this->teamseason = array();
       $this->leagueteamchampions = array();
       
       
       $mdlRankingAllTime = BaseDatabaseModel::getInstance("RankingAllTime", "sportsmanagementModel");
       //$mdlRanking = BaseDatabaseModel::getInstance("Ranking", "sportsmanagementModel");
       $mdlProject = BaseDatabaseModel::getInstance("Project", "sportsmanagementModel");
       
       $mdlTeaminfo = BaseDatabaseModel::getInstance("TeamInfo", "sportsmanagementModel");
       $mdlClubinfo = BaseDatabaseModel::getInstance("ClubInfo", "sportsmanagementModel");
       
		$this->document->addScript(Uri::root(true) . '/components/' . $this->option . '/assets/js/smsportsmanagement.js');
		$this->projectids     = $mdlRankingAllTime->getAllProject();
		$this->projectnames   = $mdlRankingAllTime->getAllProjectNames();
        
        foreach ($this->projectids as $this->count_i => $this->project_id)
		{
          $mdlProject::$projectid = $this->project_id;
        $project = $mdlProject::getProject();
		$rankinghelper = JSMRanking::getInstance($project, 0);
		$rankinghelper->setProjectId($project->id, 0);
          //echo '<pre>'.print_r($project,true).'</pre>';
          
          $mdlRanking = BaseDatabaseModel::getInstance("Ranking", "sportsmanagementModel");
		//echo '<pre>'.print_r($this->project_id,true).'</pre>';
        $mdlRanking::$projectid = $this->project_id;
          
          $mdlRanking::$round = $project->current_round;
          
          $mdlRanking::$currentRanking = array();
        $mdlRanking::computeRanking(0);
        $currentRanking = $mdlRanking::$currentRanking;
        $this->currentRanking = $this->model->_sortRanking($currentRanking[0]);
        
        if ( $project->season_name == '2019/20' )
        {
        //echo '<pre>'.print_r($this->currentRanking,true).'</pre>';  
          
        }
        
        foreach ($this->currentRanking as $this->count_i => $this->champion)
		{
        
          switch ( $this->champion->rank )
          {
            case 1:
        //if ( $this->champion->rank == 1 )
        //{
//        echo '<pre>'.print_r($project->season_name,true).'</pre>';    
//        echo '<pre>'.print_r($this->champion->_name,true).'</pre>';
        $object = new stdClass;
		$object->teamname = $this->champion->_name;
        $object->ptid_slug = $this->champion->ptid_slug;
        $object->ptid = $this->champion->_ptid;
        $object->teamid = $this->champion->_teamid;
			  $object->project_id = $project->slug;
        
        $mdlTeaminfo::$team = null;
        $teaminfo = $mdlTeaminfo::getTeam(0,$this->champion->_teamid);
        $object->clubid = $teaminfo->club_id;
			  
        $mdlClubinfo::$club = null;
        $clubinfo = $mdlClubinfo::getClub(0,$teaminfo->club_id);
		$object->logo_big = $clubinfo->logo_big;
			  
        /** welche saison zu welchem team */
        $this->teamseason[$object->teamid]['season'][] = $project->season_name;
        /* wenn nötig, array variabel initialisieren */
        if (empty($this->teamseason[$object->teamid]['title'])) {
        $this->teamseason[$object->teamid]['title'] = 0;
        }
        $this->teamseason[$object->teamid]['title'] += 1;
        /** in welcher saison hat welches team gewonnen */
        $this->leaguechampions[$project->season_name] = $object;
        /** team details */
        if ( !array_key_exists($object->teamid, $this->leagueteamchampions) ) {
        $this->leagueteamchampions[$object->teamid] = $object;
        }
         break;
        }
        
        }
        
        /** nach titel sortieren */
        //$this->teamseason
        /* Hole eine Liste von Spalten */
          
          }

$this->teamstotal = array();

		foreach ((array) $this->teamseason as $rows => $value)
		{
          
          /* echo 'value<pre>'.print_r($value['title'],true).'</pre>'; */
          
			//foreach ($value AS $row)
			//{
              
              //echo 'row<pre>'.print_r($row,true).'</pre>'; 
              
              
				$this->teamstotal[$rows]['team_id'] = $rows;
				$this->teamstotal[$rows]['total']   = $value['title'];
				//$teamstotal[$rows][$rows]   = $row->total;
			//}
		}
        /* Hole eine Liste von Spalten */
		foreach ($this->teamstotal as $key => $row)
		{
			$total[$key] = $row['total'];
		}

		array_multisort($total, SORT_DESC, $this->teamstotal);
        //echo '<pre>'.print_r($this->teamstotal,true).'</pre>';      
      
        // ksort($this->leaguechampions);
		krsort($this->leaguechampions);
		
        //echo 'in welcher saison hat welches team gewonnen <pre>'.print_r($this->leaguechampions,true).'</pre>';
        //echo 'welche saison zu welchem team <pre>'.print_r($this->teamseason,true).'</pre>';
        //echo 'team details <pre>'.print_r($this->leagueteamchampions,true).'</pre>';

      //echo '<pre>'.print_r($this->projectids,true).'</pre>';
      //echo '<pre>'.print_r($this->currentRanking,true).'</pre>';

//		$this->project_ids           = implode(",", $this->projectids);
//		$this->project_ids    = $project_ids;
//		$this->teams          = $mdlRankingAllTime->getAllTeamsIndexedByPtid($this->project_ids );
//		$this->matches        = $mdlRankingAllTime->getAllMatches($this->project_ids );
//		$this->ranking        = $mdlRankingAllTime->getAllTimeRanking();
//		$this->tableconfig    = $mdlRankingAllTime->getAllTimeParams();
//		$this->config         = $mdlRankingAllTime->getAllTimeParams();
//		$this->currentRanking = $mdlRankingAllTime->getCurrentRanking();
//		$this->action         = $this->uri->toString();
//		$this->colors         = $mdlRankingAllTime->getColors($this->config['colors']);
		/** Set page title */
		$pageTitle = Text::_('COM_SPORTSMANAGEMENT_RANKING_PAGE_TITLE');
		$this->document->setTitle($pageTitle);
		$this->warnings = $mdlProject::$projectwarnings;
        $this->tips = $mdlProject::$projecttips;
        $this->notes = $mdlProject::$projectnotes;
$this->notes = array_merge($this->notes, $mdlRankingAllTime::$rankingalltimenotes);
      $this->tips = array_merge($this->tips, $mdlRankingAllTime::$rankingalltimetips);
      $this->warnings = array_merge($this->warnings, $mdlRankingAllTime::$rankingalltimewarnings);
	}

}

