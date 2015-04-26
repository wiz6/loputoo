<?php

/**
 * Class AjaxController handles all ajax requests
 */


class EventController {
    private $data;
    private $dao;
    function __construct($data) {
        $this->data = $data;
        $this->eventDao = new EventDao();
        $this->pageDao = new PageDao();
    }

    public function doAction($action) {
        if (!$action) {
            return false;
        }
	    $res = null;
        if ($action == 'saveUserEvents') {
            $res = $this->saveUserEvents($this->data['userEvents']);
        } elseif($action == 'install') {
            $res = $this->install($this->data['database_config']);
        } elseif($action == 'getAllUsers') {
            $res = $this->getAllUsers();
        } elseif($action == 'getEventsByUserAndSession') {
            $res = $this->getEventsByUserAndSession($this->data['userID'], $this->data['sessionID']);
        }

	    echo json_encode($res);
    }

    private function install($db_config) {
	    $res = $this->writeDbConfigToFile($db_config);
	    if ($res['result']) {
		    $res = $this->pageDao->install();
	    }
        return $res;
    }
    private function saveUserEvents($data) {
        $this->eventDao->saveUserEvents($data);
    }

    private function writeDbConfigToFile($db_config) {
	    $filename = '../data.txt';

	    if (!file_exists($filename)) {
		    return array('result' => false,'msg' => 'File '.$filename.' doesnt exists!');
	    }
	    if (!is_writable($filename)) {
		    return array('result' => false, 'msg' => 'File '.$filename.' is not writable!');
	    }
	    $fopen = fopen($filename, "w");
	    if ($fopen === false) {
		    return array('result' => false, 'msg' => 'Cant open file '.$filename.'!');
	    }

	    $txt = "db_name=".$db_config['db_name']."\n";
	    $txt .= "db_user=".$db_config['db_user']."\n";
	    $txt .= "db_password=".$db_config['db_password']."\n";
	    $txt .= "db_host=".$db_config['db_host'];

	    fwrite($fopen, $txt);
	    fclose($fopen);
	    return array('result' => true);

    }

    private function getAllUsers() {
        $result = $this->eventDao->getAllUsers();
        $newData = array();
        if ($result) {
            //append sessionID-s
            foreach($result as $id) {
                $sessions = $this->eventDao->getSessionsByUserID($id);
                if (!$sessions) {
                    continue;
                }
                $newData[$id] = array('sessions' => $sessions);
            }

        }
        return array('result' => $newData);
    }

    private function getEventsByUserAndSession($userID, $sessionID) {
        if (!$sessionID || !$userID) {
            return;
        }
        $res = $this->eventDao->getEventsByUserAndSession($userID, $sessionID);

        return array('result' => $res);
    }
} 