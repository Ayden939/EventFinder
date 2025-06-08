<?php
    include_once 'Event.php';

    class EventDAO {


        public function getConnection(){
            $mysqli = new mysqli("127.0.0.1", "student", "UApass50", "EventFinderDB");
            if ($mysqli->connect_errno) {
                $mysqli=null;
            }
            return $mysqli;
        }

        public function addEvent($event){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("INSERT INTO events (user_id, title, descr, event_date, event_loc, category, latitude, longitude) VALUES (?, ?, ?, ?, ?, ?,?,?)");
            $stmt->bind_param("isssssdd", $event->getUserID(), $event->getTitle(), $event->getDescr(), $event->getEventDate(),  $event->getEventLoc(), $event->getCategory(), $event->getLat(), $event->getLong());
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function deleteEvent($eventid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("DELETE FROM events WHERE event_id = ?");
            $stmt->bind_param("i", $eventid);
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function getEvents(){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM events;");
            if (!$stmt) {
                echo "Query preparation error: " . $connection->error;
                return null; // Return null or handle the error accordingly
            }
            $stmt->execute();
            if ($stmt->error) {
                echo "Query execution error: " . $stmt->error;
                return null; // Return null or handle the error accordingly
            }
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $event = new Event();
                $event->load($row);
                $events[]=$event;
            }
            $stmt->close();
            $connection->close();
            return $events;
        }

        public function getEventsUser($userid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM events where user_id = ?;");
            $stmt->bind_param("i",$userid);
            if (!$stmt) {
                echo "Query preparation error: " . $connection->error;
                return null; // Return null or handle the error accordingly
            }
            $stmt->execute();
            if ($stmt->error) {
                echo "Query execution error: " . $stmt->error;
                return null; // Return null or handle the error accordingly
            }
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $event = new Event();
                $event->load($row);
                $events[]=$event;
            }
            $stmt->close();
            $connection->close();
            return $events;
        }

        public function authenticate($username, $passwd){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM users WHERE username = ? and pass_hash = ?;");
            $stmt->bind_param("ss",$username,$passwd);
            $stmt->execute();
            $result = $stmt->get_result();
            $found=$result->fetch_assoc();
            $stmt->close();
            $connection->close();
            var_dump($found);
            return $found;
        }

        public function updateEvent($event){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("UPDATE events SET user_id = ?, title = ?, descr = ?, event_date = ?,  event_loc = ?, category = ?, latitude = ?, longitude = ? WHERE event_id = ?");
            $stmt->bind_param("isssssddi", $event->getUserID(), $event->getTitle(), $event->getDescr(), $event->getEventDate(),  $event->getEventLoc(), $event->getCategory(), $event->getLat(), $event->getLong(), $event->getEventID());
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function getEvent($eventid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM events WHERE event_id = ?");
            $stmt->bind_param("i", $eventid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $event = new Event();
                $event->setEventID($row['event_id']);
                $event->setUserID($row['user_id']);
                $event->setTitle($row['title']);
                $event->setDescr($row['descr']);
                $event->setEventDate($row['event_date']);
                $event->setEventLoc($row['event_loc']);
                $event->setCategory($row['category']);
                $event->setLat($row['latitude']);
                $event->setLong($row['longitude']);
                $result->close();
            } else {
                $event = null;
            }
            $stmt->close();
            $connection->close();
            return $event;
        }

    }
?>
