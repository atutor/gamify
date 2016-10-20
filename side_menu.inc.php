<?php 
namespace gamify;
use gamify\PHPGamification\DAO;
/* start output buffering: */
global $savant;
ob_start(); ?>

<?php
global $_base_path;
$sql = "SELECT * FROM %sgm_options WHERE course_id=%d";
$gm_options = queryDB($sql, array(TABLE_PREFIX, $_SESSION['course_id']));

$count = 0;
foreach($gm_options as $option => $value){
    $enabled_options[$count] = $value['option'];
    $count++;
}

// this line is a hack
$this_path =  preg_replace ('#/get.php#','',$_SERVER['DOCUMENT_ROOT'].$_base_path);
require_once($this_path.'mods/gamify/gamify.lib.php');
require_once($this_path.'mods/gamify/PHPGamification/PHPGamification.class.php');

$gamification = new PHPGamification();
$gamification->setDAO(new DAO(DB_HOST, DB_NAME, DB_USER, DB_PASSWORD));
$gamification->setUserId($_SESSION['member_id']);

if(in_array('showpoints',$enabled_options)){
   showUserScore($gamification, $_SESSION['course_id']);
}
if(in_array('showlevels',$enabled_options)){
    showUserLevel($gamification, $_SESSION['course_id']);
}
if(in_array('showprogress',$enabled_options)){
    echo showUserProgress($gamification, $_SESSION['course_id']);
}
if(in_array('showbadges',$enabled_options)){
    echo showUserBadge($gamification, $_SESSION['course_id']);
}
if(in_array('showleaders',$enabled_options)){
    echo getLeaders($gamification, get_leader_count());
}
if(in_array('showposition',$enabled_options)){
    echo yourPosition($_SESSION['member_id']);
}

$savant->assign('dropdown_contents', ob_get_contents());
ob_end_clean();

$savant->assign('title', _AT('gamify')); // the box title
$savant->display('include/box.tmpl.php');
?>