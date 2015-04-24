<?php
/**
 * Created by PhpStorm.
 * User: test
 * Date: 18.04.15
 * Time: 15:37
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

        if ($action == 'sendUserEvents') {
            $this->sendUserEvents($this->data['userEvents']);
        } elseif($action == 'install') {

            $this->install($this->data['database_config']);
        }
    }

    private function install($db_config) {
        $this->writeDbConfigToFile($db_config);
        $this->eventDao->install();
    }
    private function sendUserEvents($data) {
        echo "send data to db".var_dump($data);
        $this->eventDao->saveUserEvents($data);
    }

    private function writeDbConfigToFile($db_config) {
        $myfile = fopen("../data.txt", "w") or die("Unable to open file!");
        $txt = "db_name=".$db_config['db_name']."\n";
        $txt .= "db_user=".$db_config['db_user']."\n";
        $txt .= "db_password=".$db_config['db_password']."\n";
        $txt .= "db_host=".$db_config['db_host'];

        fwrite($myfile, $txt);
        fclose($myfile);
    }
} 