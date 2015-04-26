<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 26.04.15
 * Time: 19:23
 */

class Dao {
    protected $db_name = null;
    protected $db_user = null;
    protected $db_password = null;
    protected $db_host = null;
    protected $conn = null;

    protected function createConnection() {
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
} 