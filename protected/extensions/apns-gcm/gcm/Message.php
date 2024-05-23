<?php

namespace PHP_GCM;

class Message {

    private $collapseKey;
    private $delayWhileIdle;
    private $dryRun;
    private $timeToLive;
    private $data;
    private $title;
    private $icon;
    private $message;
    private $sound;
    private $notifications;
    private $restrictedPackageName;

    /**
     * Message Constructor
     *
     * @param string $collapseKey
     * @param bool $delayWhileIdle
     * @param bool $dryRun
     * @param int $timeToLive
     * @param array $data
     * @param string $restrictedPackageName
     */
    public function __construct($collapseKey = '', array $data = array(), $timeToLive = -1, $delayWhileIdle = '',
                                $restrictedPackageName = '', $dryRun = false) {
        $this->collapseKey = $collapseKey;

        if($delayWhileIdle != '')
            $this->delayWhileIdle = $delayWhileIdle;

        $this->dryRun = $dryRun;
        $this->timeToLive = $timeToLive;
        $this->data = $data;
        $this->restrictedPackageName = $restrictedPackageName;
    }

    /**
     * Sets the collapseKey property.
     *
     * @param string $collapseKey
     */
    public function collapseKey($collapseKey) {
        $this->collapseKey = $collapseKey;
    }

    /**
     * Gets the collapseKey property
     *
     * @return string
     */
    public function getCollapseKey() {
        return $this->collapseKey;
    }

    public function setNotification($params) {
        $this->notifications = $params;
    }
    
    public function getNotifications() {
        if($this->title != '')
        {
            $this->notifications['title'] = $this->title;
        }
        
        if($this->message != '')
        {
            $this->notifications['body'] = $this->message;
        }

        if($this->icon != '')
        {
            $this->notifications['icon'] = $this->icon;
        }
        
        if($this->sound != '')
        {
            $this->notifications['sound'] = $this->sound;
        }
        return $this->notifications;
    }
    
    /**
     * Sets the delayWhileIdle property (default value is {false}).
     *
     * @param bool $delayWhileIdle
     */
    public function delayWhileIdle($delayWhileIdle) {
        $this->delayWhileIdle = $delayWhileIdle;
    }

    /**
     * Gets the delayWhileIdle property
     *
     * @return bool
     */
    public function getDelayWhileIdle() {
        if(isset($this->delayWhileIdle))
            return $this->delayWhileIdle;
        return null;
    }

    /**
     * Sets the dryRun property (default value is {false}).
     *
     * @param bool $dryRun
     */
    public function dryRun($dryRun) {
        $this->dryRun = $dryRun;
    }

    /**
     * Gets the dryRun property
     *
     * @return bool
     */
    public function getDryRun() {
        return $this->dryRun;
    }

    /**
     * Sets the time to live, in seconds.
     *
     * @param int $timeToLive
     */
    public function timeToLive($timeToLive) {
        $this->timeToLive = $timeToLive;
    }

    /**
     * Gets the timeToLive property
     *
     * @return int
     */
    public function getTimeToLive() {
        return $this->timeToLive;
    }

    /**
     * Adds a key/value pair to the payload data.
     *
     * @param string $key
     * @param string $value
     */
    public function addData($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Sets the data property
     *
     * @param array $data
     */
    public function data(array $data) {
        $this->data = $data;
    }

    /**
     * Gets the data property
     *
     * @return array
     */
    public function getData() {
        return $this->data;
    }

    /**
     * Sets the restrictedPackageName property.
     *
     * @param string $restrictedPackageName
     */
    public function restrictedPackageName($restrictedPackageName) {
        $this->restrictedPackageName = $restrictedPackageName;
    }

    /**
     * Gets the restrictedPackageName property
     *
     * @return string
     */
    public function getRestrictedPackageName() {
        return $this->restrictedPackageName;
    }
}