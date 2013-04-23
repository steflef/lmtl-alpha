<?php

namespace CQAtlas\Helpers;

require_once 'vendor/google-api-php-client/src/Google_Client.php';
require_once "vendor/google-api-php-client/src/contrib/Google_DriveService.php";
require_once "vendor/google-api-php-client/src/contrib/Google_Oauth2Service.php";

class GoogleDrive
{
    private $_client;
    private $_service;
    private $_driveFile;
    private $_mimeType = 'application/vnd.ms-excel';

    public function __construct($name='', $desc='')
    {


        $DRIVE_SCOPE = 'https://www.googleapis.com/auth/drive';
        $SERVICE_ACCOUNT_EMAIL = '509163819657@developer.gserviceaccount.com';
        $SERVICE_ACCOUNT_PKCS12_FILE_PATH = '/Users/prof_lebrun/Downloads/a56f4e11d50f76ede6c83f4c0fad55ea1741b20b-privatekey.p12';

        $key = file_get_contents($SERVICE_ACCOUNT_PKCS12_FILE_PATH);
        $auth = new \Google_AssertionCredentials(
            $SERVICE_ACCOUNT_EMAIL,
            array($DRIVE_SCOPE),
            $key);

        $this->_client = new \Google_Client();
        $this->_client->setUseObjects(true);
        $this->_client->setAssertionCredentials($auth);

        $this->_service = new \Google_DriveService($this->_client);
        $this->_driveFile = new \Google_DriveFile();
        $this->setFileProperties();
    }

    private function setFileProperties()
    {

        $this->_driveFile->setTitle('CQ ATLAS -- UPLOAD EXCEL');
        $this->_driveFile->setDescription('Ma Description ... test');
        $this->_driveFile->setMimeType($this->_mimeType);
        $this->_driveFile->setOwnerNames(array('prof.lebrun@gmail.com'));
        $this->_driveFile->setParents(array(
            array(
                'kind'=> 'drive#fileLink',
                'id'=>'0B7Dvk7BRZ_yjdnZXNDBwR2xRbHM' // UPLOADED
            )
        ));
        return $this;
    }

    public function upload($dir='',$fileName='')
    {
        $createdFile = $this->_service->files->insert($this->_driveFile, array(
            'data' => file_get_contents($dir.'/'.$fileName.'.xlsx'),
            'mimeType' => $this->_mimeType,
            'convert' => true
        ));
        return $createdFile;
    }
}