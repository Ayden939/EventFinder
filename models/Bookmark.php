<?php
    class Bookmark implements JsonSerializable{
        private $userID;
        private $eventID;
        private $notify_date;
        private $is_notified;

        public function load($row){
            $this->setUserID($row["user_id"]);
            $this->setEventID($row['event_id']);
            $this->setNotifyDate($row['notify_date']);
            $this->setIsNotified($row["is_notified"]);
        }

        public function setUserID($userID){
            $this->userID=$userID;
        }

        public function getUserID(){
            return $this->userID;
        }

        public function setEventID($eventID){
            $this->eventID=$eventID;
        }

        public function getEventID(){
            return $this->eventID;
        }

        public function setNotifyDate($notify_date){
            $this->notify_date=$notify_date;
        }

        public function getNotifyDate(){
            return $this->notify_date;
        }

        public function setIsNotified($is_notified){
            $this->is_notified=$is_notified;
        }

        public function getIsNotified(){
            return $this->is_notified;
        }

        public function jsonSerialize(){
            return array(
                'userID' => $this->userID,
                'eventID' => $this->eventID,
                'notify_date'=> $this->notify_date,
                'is_notified' => $this->is_notified
            );
        }
    }
?>
