<?php

class PageDao extends Dao{
    public function __construct(){
        self::createConnection();
    }

    public function install() {
        $res = self::createConnection();
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
            $sql .= 'elementID varchar(100) DEFAULT NULL,';
            $sql .= 'isMobile int DEFAULT NULL,';
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