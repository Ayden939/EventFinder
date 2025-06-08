<?php
    class Event implements JsonSerializable{
        private $eventID;
        private $userID;
        private $title;
        private $descr;
        private $event_date;
        private $event_loc;
        private $category;
        private $latitude;
        private $longitude;

        public function load($row){
            $this->setEventID($row['event_id']);
            $this->setUserID($row["user_id"]);
            $this->setTitle($row['title']);
            $this->setDescr($row["descr"]);
            $this->setEventDate($row["event_date"]);
            $this->setEventLoc($row["event_loc"]);
            $this->setCategory($row["category"]);
            $this->setLat($row["latitude"]);
            $this->setLong($row["longitude"]);
        }

        public function setEventID($eventID){
            $this->eventID=$eventID;
        }

        public function getEventID(){
            return $this->eventID;
        }

        public function setUserID($userID){
            $this->userID=$userID;
        }

        public function getUserID(){
            return $this->userID;
        }

        public function setTitle($title){
            $this->title=$title;
        }

        public function getTitle(){
            return $this->title;
        }

        public function setDescr($descr){
            $this->descr=$descr;
        }

        public function getDescr(){
            return $this->descr;
        }

        public function setEventDate($event_date){
            $this->event_date=$event_date;
        }

        public function getEventDate(){
            return $this->event_date;
        }

        public function setEventLoc($event_loc){
            $this->event_loc=$event_loc;
        }

        public function getEventLoc(){
            return $this->event_loc;
        }

        public function setCategory($category){
            $this->category=$category;
        }

        public function getCategory(){
            return $this->category;
        }

        public function setLat($latitude){
            $this->latitude=$latitude;
        }

        public function getLat(){
            return $this->latitude;
        }

        public function setLong($longitude){
            $this->longitude=$longitude;
        }

        public function getLong(){
            return $this->longitude;
        }

        public function jsonSerialize(){
            return array(
                'eventID' => $this->eventID,
                'userID' => $this->userID,
                'title'=> $this->title,
                'descr' => $this->descr,
                'event_date' => $this->event_date,
                'event_loc' => $this->event_loc,
                'category' => $this->category,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude
            );
        }
    }
?>
