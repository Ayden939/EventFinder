<?php
    include_once 'Bookmark.php';

    class BookmarkDAO {


        public function getConnection(){
            $mysqli = new mysqli("127.0.0.1", "student", "UApass50", "EventFinderDB");
            if ($mysqli->connect_errno) {
                $mysqli=null;
            }
            return $mysqli;
        }

        public function addBookmark($bkmk){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("INSERT INTO bookmarks (user_id, event_id, notify_date, is_notified) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $bkmk->getUserID(), $bkmk->getEventID(), $bkmk->getNotifyDate(), $bkmk->getIsNotified());
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function deleteBookmark($userid,$eventid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("DELETE FROM bookmarks WHERE user_id = ? and event_id = ?");
            $stmt->bind_param("ii", $userid,$eventid);
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function getBookmarks(){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM bookmarks;");
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
                $bkmk = new Bookmark();
                $bkmk->load($row);
                $bkmks[]=$bkmk;
            }
            $stmt->close();
            $connection->close();
            return $bkmks;
        }

        public function getBookmarksUser($userid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM bookmarks where user_id = ?;");
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
                $bkmk = new Bookmark();
                $bkmk->load($row);
                $bkmks[]=$bkmk;
            }
            $stmt->close();
            $connection->close();
            return $bkmks;
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

        public function updateBookmark($bkmk){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("UPDATE bookmarks SET notify_date = ?, is_notified = ? WHERE user_id = ? and event_id = ?");
            $stmt->bind_param("ssii",  $bkmk->getNotifyDate(),  $bkmk->getIsNotified(), $bkmk->getUserID(), $bkmk->getEventID());
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function getBookmark($userid,$eventid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM bookmarks WHERE user_id = ? and event_id = ?");
            $stmt->bind_param("ii", $userid, $eventid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $bkmk = new Bookmark();
                $bkmk->setEventID($row['event_id']);
                $bkmk->setUserID($row['user_id']);
                $bkmk->setNotifyDate($row['notify_date']);
                $bkmk->setIsNotified($row['is_notified']);
                $result->close();
            } else {
                $bkmk = null;
            }
            $stmt->close();
            $connection->close();
            return $bkmk;
        }

    }
?>
