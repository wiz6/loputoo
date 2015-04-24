<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 18.04.15
 * Time: 17:55
 */

class EventDao {
    private $db_name = null;
    private $db_user = null;
    private $db_password = null;
    private $db_host = null;
    private $conn = null;
    public function __construct(){
        $this->createConnection();
    }

	/**
	 * @param mixed array $data
	 * @return bool
	 */
    public function saveUserEvents($data) {
        if (!$data || !is_array($data)) {
            return;
        }
	    foreach($data as $event) {
		    error_log($event['time']);
		    $sql = 'INSERT INTO userEvents SET time=?, posX=?, posY=?, location=?, sessionID=?, userID=?, eventName=?, keyCode=?';
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
				    !empty($event['keyCode']) ? $event['keyCode'] : null)
		    );
	    }
	    return true;
    }

    private function createConnection() {
        $file = file("../data.txt");
        if (!$file) {
            return;
        }
        $conf = array();
        foreach($file as $line) {
            $field = explode('=', $line);
            $conf[$field[0]] = $field[1];
        }
        $this->db_name = !empty($conf['db_name']) ? trim($conf['db_name']) : null;
        $this->db_user = !empty($conf['db_user']) ? trim($conf['db_user']) : null;
        $this->db_password = !empty($conf['db_password']) ? trim($conf['db_password']) : null;
        $this->db_host = !empty($conf['db_host']) ? trim($conf['db_host']) : null;

        try {

            $this->conn = new PDO("mysql:host=".$this->db_host.";dbname=".$this->db_name.";", $this->db_user, $this->db_password,array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ));
	        return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }

    public function install() {
        $res = $this->createConnection();
	    if (!$res) {
		    return array('result' => false, 'msg' => 'Wrong data!');
	    }
        try {

            $sql = 'CREATE TABLE IF NOT EXISTS userEvents(';
            $sql .= 'time BIGINT DEFAULT NULL,';
            $sql .= 'posX int DEFAULT NULL,';
            $sql .= 'posY int DEFAULT NULL,';
            $sql .= 'location varchar(300) DEFAULT NULL,';
            $sql .= 'sessionID varchar(30) NOT NULL,';
	        $sql .= 'eventName varchar(20) NOT NULL,';
	        $sql .= 'keyCode INT DEFAULT NULL,';
            $sql .= 'userID varchar(20) NOT NULL)';

            $sth = $this->conn->prepare($sql);
            $sth->execute();

            return array('result' => true);
        }
        catch(PDOException $e) {
            return array('result' => false, 'msg' => $e->getMessage());
        }
    }
} 