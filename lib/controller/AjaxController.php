<?php

/**
 * Class AjaxController handles all ajax requests
 */


class AjaxController {
    private $data;
    private $dao;
    function __construct($data) {
        $this->data = $data;
        $this->eventDao = new EventDao();
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
        }
	    echo json_encode($res);
    }

    private function install($db_config) {
	    $res = $this->writeDbConfigToFile($db_config);
	    if ($res['result']) {
		    $res = $this->eventDao->install();
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
} 