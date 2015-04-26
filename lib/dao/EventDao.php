<?php

class EventDao extends Dao{
    public function __construct(){
        $this->createConnection();
    }

	/**
     * Saves user events into database with in one query
	 * @param mixed array $data
	 * @return bool
	 */
    public function saveUserEvents($data) {
        if (!$data || !is_array($data)) {
            return;
        }
	    foreach($data as $event) {
		    $sql = 'INSERT INTO userEvents SET time=?, posX=?, posY=?, location=?, sessionID=?, userID=?, eventName=?, keyCode=?, elementID=?';
		    $query = $this->conn->prepare($sql);
		    $query->execute(
			    array(
				    !empty($event['time']) ? (float)$event['time'] : null,
				    !empty($event['posX']) ? $event['posX'] : null,
				    !empty($event['posY']) ? $event['posY'] : null,
				    !empty($event['location']) ? $event['location'] : null,
				    !empty($event['sessionID']) ? $event['sessionID'] : null,
				    !empty($event['userID']) ? $event['userID'] : null,
				    !empty($event['eventName']) ? $event['eventName'] : null,
				    !empty($event['keyCode']) ? $event['keyCode'] : null,
                    !empty($event['elementID']) ? $event['elementID'] : null)
		    );
	    }
	    return true;
    }

    /**
     * Get all users
     * @return mixed array $result
     */
    public function getAllUsers() {
        $sql = 'SELECT distinct userID FROM userEvents';

        $result = array();
        foreach($this->conn->query($sql) as $data) {
            $result[] = $data['userID'];
        }
        return $result;
    }

    /**
     * Get all events by user id
     * @param int $userID
     * @return mixed array
     */
    public function getEventsByUserId($userID) {
        if (!$userID) {
            return;
        }
        $sql = 'SELECT * FROM userEvents WHERE userID=?';
        $query = $this->conn->prepare($sql);
        $query->execute(array($userID));

        return $query->fetchAll();
    }

    /**
     * Get all user events per session
     * @param int $sessionID
     * @return mixed array
     */
    public function getEventsBySessionId($sessionID) {
        if (!$sessionID) {
            return;
        }
        $sql = 'SELECT * FROM userEvents WHERE sessionID=?';
        $query = $this->conn->prepare($sql);
        $query->execute(array($sessionID));

        return $query->fetchAll();
    }

    public function getSessionsByUserID($userID) {
        if (!$userID) {
            return;
        }
        $sql = 'Select distinct sessionID, location from userEvents where userID=?';
        $query = $this->conn->prepare($sql);
        $query->execute(array($userID));

        return $query->fetchAll();
    }

    public function getEventsByUserAndSession($userID, $sessionID) {
        $sql = 'Select * from userEvents where userID=? AND sessionID=?';
        $query = $this->conn->prepare($sql);
        $query->execute(array($userID, $sessionID));

        return $query->fetchAll();
    }

    public function getMostPopularArea($radius) {

    }

    public function getMostUnPopularArea($radius) {

    }

    public function getMostPopularElemnentID() {

    }
    public function getMostUnPopularElementID() {

    }


} 