<?php      
   // load up your config file  
   require_once("resources/config.php");
   require_once("resources/library/golftracker.inc.php");
   
   // Connect to database
   $db = new mysqli($config['db']['host'], $config['db']['username'], $config['db']['password'], $config['db']['dbname']);
   if($db->connect_errno > 0){ die('Unable to connect to database [' . $db->connect_error . ']'); }
   
   // load up your page header
   $page_title = "Golf Tracker Home"; 
   require_once(TEMPLATES_PATH . "/header.php");
?>  
<div id="container">  
<?php
   // Initialize arrays for Player and Course Objects
   $playersArray = array(); $coursesArray = array();
   // Get all Players from DB and store in Player array
   $playersArray = getPlayersFromDB($db, "");
   // Add Player Rounds from DB to each Player Object in array
   foreach ($playersArray as $player_row) {
      addRoundsGivenPlayerFromDB($db, $player_row);
   }
   // Get all Courses from DB and store in Courses Array
   $coursesArray = getCoursesFromDB($db, "");
  
  // Let's do some data manipulation //
  
  // Print Player Info for each player
  foreach ($playersArray as $player) {
     // Print Player Demographics and Handicap
     print "<div id='player" . $player->getPlayerId() . "'>";
     print $player->printPlayerInfo();
     print " Handicap: ";
     print $player->getPlayerHandicap();
     
     // Print Player's rounds
     print "\n<br />Rounds Played by Player:\n<ul id='player" . $player->getPlayerId() . "-rounds'>";
     foreach ($player->getPlayerRounds() as $playerrounds) {
        print "\n<li>";
        print $playerrounds->printRoundInfo();
        print "</li>";
     }
     print "\n</ul>";
     print "\n</div>";
  }
  
  print "\n<hr>";
  // Print Course Information and Player Rounds at Each Course
  foreach ($coursesArray as $courserow) {
     print "<div id='course" . $courserow->getCourseId() . "'>";
     print $courserow->printCourseInfo();
     $c_name = $courserow->getCourseName();
     
     // Print Player Rounds played at particular course
     print "\n<br />Rounds Played at Course:\n<ul id='course" . $courserow->getCourseId() . "-rounds'>";
     // Iterate thru all player rounds to determine if a round was played at this course.
     $playerRoundArray = array();
     foreach ($playersArray as $chkplayer) {
        $myplayerrounds = $chkplayer->getPlayerRoundsGivenCourseName($c_name);
        $playerRoundArray = array_merge($playerRoundArray,$myplayerrounds);
     }
     foreach ($playerRoundArray as $player_round_row) {
        print "\n<li>"; 
        //print $player_round_row->getFirstName() . " " . $player_round_row->getLastName();
        print $player_round_row->getRoundScore();
        print " scored on " . $player_round_row->getRoundDatePlayed();
        print ".</li>";
     }
     print "\n</ul>";
     print "\n</div>";
  }
?>
<?php  
   require_once(TEMPLATES_PATH . "/rightpanel.php");  
?>  
</div>  
<?php  
    require_once(TEMPLATES_PATH . "/footer.php");
    
    // Close Connection to Database
    $db->close();
?>  