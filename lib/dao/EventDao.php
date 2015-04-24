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
    public function saveUserEvents($data) {
        if (!$data || !is_array($data)) {
            return;
        }

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
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function install() {
        $this->createConnection();
        try {


            $sql = 'CREATE TABLE IF NOT EXISTS userEvents(';
            $sql .= 'time INT NOT NULL,';
            $sql .= 'posX INT NOT NULL,';
            $sql .= 'posY INT NOT NULL,';
            $sql .= 'location INT NOT NULL,';
            $sql .= 'sessionID varchar(30) NOT NULL,';
            $sql .= 'user varchar(20) NOT NULL)';

            $sth = $this->conn->prepare($sql);
            $sth->execute();

            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }
} 