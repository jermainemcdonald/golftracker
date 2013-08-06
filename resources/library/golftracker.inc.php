<?php      
/* Player Class */
class Player {
   /* properties */
   var $pid, $last_name, $first_name, $gender, $email, $phone_number;
   // Array to store player's rounds as $rounds['YYYY-MM-DD'] = round object;
   var $rounds = array();
   
   /* Getter Methods */
   function getPlayerId() { return $this->pid; }
   function getPlayerLastName() { return $this->last_name; }
   function getPlayerFirstName() { return $this->first_name; }
   function getPlayerGender() { return $this->gender; }
   function getPlayerEmail() { return $this->email; }  
   function getPlayerPhoneNumber() { return $this->phone_number; }
   function getPlayerRounds() { return $this->rounds; }
   
   /* Setter Methods */
   function setPlayerId($p_pid) { $this->pid = $p_pid; }
   function setPlayerLastName($p_last_name) { $this->last_name = $p_last_name; }
   function setPlayerFirstName($p_first_name) { $this->first_name = $p_first_name; }
   function setPlayerGender($p_gender) { $this->gender = $_gender; }
   function setPlayerEmail($p_email) { $this->email = $p_email; }  
   function setPlayerPhoneNumber($p_phone_number) { $this->phone_number = $p_phone_number; }
   
   /* Constructor */
   function __construct($p_pid, $p_last, $p_first, $p_gender, $p_email, $p_phone_number) {
      $this->pid = $p_pid;
      $this->last_name = $p_last;
      $this->first_name = $p_first;
      $this->gender = $p_gender;
      $this->email = $p_email;
      $this->phone_number = $p_phone_number;   
   }
   
   /* Player Methods */
   /* Method: addPlayerRound ( Round object)
    * This method adds a round to the array of Player rounds.
    * It adds the round to the end of the array and assumes each round has a date in it.
    */
   function addPlayerRound ($my_round) {
      $my_date_played = $my_round->getRoundDatePlayed();
      $this->rounds[$my_date_played] = $my_round;
      krsort($this->rounds); // Re-sort round array to be ordered by date desc, 2013-07-01, 2013-06-01, etc.
      return 1;
   }
   
   /* Method: printPlayerInfo()
    * Prints the player properties, except rounds, as a single line string
    */
   function printPlayerInfo() {
      $pinfo_string = $this->first_name . " "
      		    . $this->last_name . " ("
      		    . $this->gender . ", "
      		    . $this->email . ", "
      		    . $this->phone_number . ")";
      return $pinfo_string;
   }
   
   /* Method: printPlayerRounds()
    * Print the player rounds
    */ 
   function printPlayerRounds() {
      if ( !sizeof($this->rounds) ) return "No Player Rounds Exist.";
      $pr_info_string = "";
      foreach ($this->rounds as $my_round) {
         $pr_info_string = $pr_info_string . $my_round->printRoundInfo();
      }
      return $pr_info_string;
   }
   
   /* Method: getPlayerHandicap()
    * Calculates Player handicap for each round played. 
    * Returns lowest value from amongst the latest five rounds.
    */
   function getPlayerHandicap() {
      $counter = 0;
      $my_handicap = 10000; // Set an absurdly high initial handicap
      foreach ($this->rounds as $rnd_row) {
         if ( $rnd_row->getRoundHandicap() < $my_handicap ) { 
            $my_handicap = $rnd_row->getRoundHandicap();
         }
         ++$counter;
         if ($counter >= 5) { break; } // Break foreach loop, only need the latest five rounds
      }
      return $my_handicap;
   }
   
   /* Method: getPlayerRoundsGivenCourseName( String) 
    * Returns array of Player Rounds where Round was played at the given Course name
    * Returns empty array if Player has played no rounds at given course.
    */
   function getPlayerRoundsGivenCourseName ($c_name) {
      $my_rounds_at_course = array();
      foreach ($this->rounds as $my_rounds_row) {
         // If the round's course name matches the given course name, add the round to the array
         if ($my_rounds_row->getRoundCourseName() == $c_name) {
            //print "MATCH-";
            array_push($my_rounds_at_course, $my_rounds_row);
         }
      }
      return $my_rounds_at_course;
   }
}

/* Course Class */
class Course {
   /* properties */
   var $cid, $name, $address, $location, $rating, $slope;
   
   /* Getter Functions */
   function getCourseId() { return $this->cid; }
   function getCourseName() { return $this->name; }
   function getCourseAddress() { return $this->address; }
   function getCourseLocation() { return $this->location; }
   function getCourseRating() { return $this->rating; }
   function getCourseSlope() { return $this->slope; }
   
   /* Setter Functions */
   function setCourseId($c_cid) { $this->cid = $c_cid; }
   function setCourseName($c_name) { $this->name = $c_name; }
   function setCourseAddress($c_address) { $this->address = $c_address; }
   function setCourseLocation($c_location) { $this->location = $c_location; }
   function setCourseRating($c_rating) { $this->rating = $c_rating; }
   function setCourseSlope($c_slope) { $this->slope = $c_slope; }
   
   /* Constructor */
   function __construct($c_cid, $c_name, $c_address, $c_location, $c_rating, $c_slope) {
      $this->cid = $c_cid;
      $this->name = $c_name;
      $this->address = $c_address;
      $this->location = $c_location;
      $this->rating = $c_rating;
      $this->slope = $c_slope;   
   }
   
   /* Method: printCourseInfo()
    * Prints the course properties as a single line string
    */
   function printCourseInfo() {
      $cinfo_string = $this->name . " ("
      		    . $this->address . ", "
      		    . $this->location . ", Rating: "
      		    . $this->rating . ", Slope: "
      		    . $this->slope . ")";
      return $cinfo_string;
   }
}

/* Round Class */
class Round {
   var $date_played, $score, $round_handicap;
   var $course; // Stores a Course Object linking the round to a particular course.
   
   /* Getter Methods */
   function getRoundDatePlayed() { return $this->date_played; }
   function getRoundScore() { return $this->score; }
   function getRoundHandicap() { return $this->round_handicap; }
   function getRoundCourseName() { return $this->course->getCourseName(); }
   
   /* Setter Methods */
   function setRoundDatePlayed($r_dateplayed) { $this->date_played = $r_dateplayed; }
   function setRoundScore($r_score) { $this->score = $r_score; }
   
   /* Constructor Method */
   function __construct($r_course, $r_date_played, $r_score) {
      $this->course = $r_course;
      $this->date_played = $r_date_played;
      $this->score = $r_score;
      $this->setRoundHandicap();
   }
   /* Method: setRoundHandicap()
    * Sets the handicap score for this round. Must have course data or will return 0.
    */
   function setRoundHandicap() {
      // If Course info not attached to this round, cannot determine handicap
      //if ( !isset($course) ) { return 0; } // Return 0 for failure.
      
      // Calculate round handicap
      $rndhcap = ($this->score - $this->course->getCourseRating()) * 113;
      $rndhcap = ($rndhcap / $this->course->getCourseSlope()) * 0.96;
      // Round to the nearest second decimal.
      $this->round_handicap = round($rndhcap, 2);
      return 1; // For Success
   }
   
   /* Method: printRoundInfo()
    * Prints Round properties as a single line string
    */
   function printRoundInfo() {
      $rinfo_string = $this->course->getCourseName() . ", "
      		    . $this->date_played . ", "
      		    . "Score: " . $this->score . ", "
      		    . "Round Handicap: " . $this->round_handicap;
      return $rinfo_string;
   }
}

/* General Functions */
/* Function: getPlayersFromDB( Database Handle, Where Clause )
 * Select all field data from Player Table. Optional WHERE clause can limit query return
 */
function getPlayersFromDB ($dbh, $where_clause) {
   $sql_statement = "SELECT * FROM Player $where_clause";
   if(!$result = $dbh->query($sql_statement)){ die('Error running query: ' . $dbh->error); }
   $my_players_array = array();
   while ($p_row = $result->fetch_assoc()) {
      // Create Player Object with the Row Info and push Player into myPlayersArray
      $current_player = new Player($p_row['ID'],$p_row['Last_Name'],$p_row['First_Name'],$p_row['Gender'],
      				   $p_row['Email'],$p_row['Phone_Number']);
      array_push($my_players_array, $current_player);
   }
   $result->free();
   // Return the array of Players
   return $my_players_array;
}


/* Function: getCoursesFromDB( Database Handle, Where Clause )
 * Select all field data from Player Table. Optional WHERE clause can limit query return
 */
function getCoursesFromDB ($dbh, $where_clause) {
   $sql_statement = "SELECT * FROM Course $where_clause";
   if(!$c_result = $dbh->query($sql_statement)){ die('Error running query: ' . $dbh->error); }
   $my_courses_array = array();
   while ($c_row = $c_result->fetch_assoc()) {
      // Create Course Object with the row Info and push Course into my_courses_array
      $current_course = new Course($c_row['ID'],$c_row['Course_Name'],$c_row['Course_Address'],$c_row['Location'],
      				   $c_row['Course_Rating'],$c_row['Course_Slope']);
      array_push($my_courses_array, $current_course);
   }
   $c_result->free();
   // Return the array of Courses
   return $my_courses_array;
}

/* Function: addRoundsGivenPlayerFromDB( Database Handle, Player Object )
 * Returns array of all the rounds a player has played.
 * Rounds will store the course information
 */
function addRoundsGivenPlayerFromDB ($dbh, $player_object) {
   $my_player_id = $player_object->getPlayerId();
   $sql_statement = "SELECT Round.ID, Round.Date_Played, Round.Score, Course.ID AS Course_ID, Course.Course_Name, "
   		  . "Course.Course_Address, Course.Location, Course.Course_Rating, Course.Course_Slope "
   		  . "FROM  Round JOIN Player ON Player.ID = Round.Player_ID JOIN Course ON Course.ID = Round.Course_ID "
   		  . "WHERE Round.Player_ID = $my_player_id";
   if(!$r_result = $dbh->query($sql_statement)){ die('Error running query: ' . $dbh->error); }
   $my_rounds_array = array();
   while ($r_row = $r_result->fetch_assoc()) {
      // Create Course Object with the row Info
      $current_course = new Course($r_row['Course_ID'],$r_row['Course_Name'],$r_row['Course_Address'],$r_row['Location'],
      				   $r_row['Course_Rating'],$r_row['Course_Slope']);
      // Create Round Object with the Row Info and push Round into my_rounds_array
      $current_round = new Round($current_course, $r_row['Date_Played'], $r_row['Score']);
      $player_object->addPlayerRound($current_round);
   }
   $r_result->free();
   // Return Success
   return 1;
} 
?>