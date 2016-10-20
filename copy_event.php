<?php
namespace gamify\PHPGamification;
use Exception;
use gamify\PHPGamification;
use gamify\PHPGamification\Model;
use gamify\PHPGamification\Model\Event;
use gamify\PHPGamification\Model\Badge;

define('AT_INCLUDE_PATH', '../../include/');
require (AT_INCLUDE_PATH.'vitals.inc.php');

$_GET["id"] = intval($_GET["id"]);

if($_GET["id"]!= ''){
    global $_base_path;
    $course_id = $_SESSION['course_id'];
    $this_path =  preg_replace ('#/get.php#','',$_SERVER['DOCUMENT_ROOT'].$_base_path);
    $sql = "SELECT * FROM %sgm_events WHERE id=%d AND course_id=%d";
    $default_event = queryDB($sql, array(TABLE_PREFIX, $_GET["id"], 0), TRUE);

        require_once($this_path.'mods/gamify/gamify.lib.php');
        require_once($this_path.'mods/gamify/PHPGamification/PHPGamification.class.php');
        $gamification = new PHPGamification();
        $gamification->setDAO(new DAO(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD));
        $event = new Event();
        
        if($default_event['id']){
            $event->setId($default_event['id'], $_SESSION['course_id']);
        }

        if(isset($_SESSION['course_id'])){
            $event->setCourseId($_SESSION['course_id']);
        } 
        $event->setAlias($default_event['alias']);
        
        if($default_event['description']){
            $event->setDescription($default_event['description']);
        }

        if($default_event['allow_repetitions']){
            $event->setAllowRepetitions($default_event['allow_repetitions']);
        }
        
         if($default_event['reach_required_repetitions']){
            $event->setReachRequiredRepetitions($default_event['reach_required_repetitions']);
        }
        
        if($default_event['max_points']){
            $event->setMaxPointsGranted($default_event['max_points']);
        }
        
        if($default_event['each_points']){
            $event->setEachPointsGranted($default_event['each_points']);
        }
        
        if($default_event['reach_points']){
            $event->setReachPointsGranted($default_event['reach_points']);
            //$event->setReachPointsGranted($_POST['reach_points']);
        }   
         
        if($default_event['id_each_badge']){
            $event->copyEachBadgeGranted($default_event['id']);
        }
        
         if($default_event['id_reach_badge']){
            $event->copyReachBadgeGranted($default_event['id']);
         }
         
        if($default_event['each_callback']){
            $event->setEachCallback($default_event['each_callback']);
        }
        if($default_event['reach_callback']){
            $event->setReachCallback($default_event['reach_callback']);
        }     

       if( $gamification->addEvent($event, $course_id)){
            $msg->addFeedback('EVENT_COPIED');
            header("Location: ".AT_BASE_HREF."mods/gamify/index_instructor.php");
            exit;
       } else{
            $msg->addError('EVENT_COPY_FAILED');
            header("Location: ".AT_BASE_HREF."mods/gamify/index_instructor.php");
            exit;
       }
}

?>