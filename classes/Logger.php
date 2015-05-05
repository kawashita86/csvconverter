<?php

require_once 'log/KLogger.php';

class LoggerCore
{
    public static $klogger;
    /** @var integer Log id */
    public $id_log;

    /** @var integer Log severity */
    public $severity;

    /** @var integer Error code */
    public $error_code;

    /** @var string Message */
    public $message;

    /** @var string Object type (eg. Order, Customer...) */
    public $object_type;

    /** @var integer Object ID */
    public $object_id;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;


    static function init()
    {
            self::$klogger = new KLogger (_LOG_FILE_PATH_TEST_, _LOG_FILE_MIN_SEVERITY_);
    }

    protected static $is_present = array();

    /**
     * Send e-mail to the shop owner only if the minimal severity level has been reached
     *
     * @param Logger
     * @param unknown_type $log
     */
    public static function sendByMail(Logger $log)
    {
        $addresses = explode(",", _CL_TECHNICAL_SUPPORT_EMAIL_ADDRESSES_);
        if (intval(Configuration::get('PS_LOGS_BY_EMAIL')) <= intval($log->severity))
            foreach ($addresses as $to) {
                Mail::Send(
                    (int)Configuration::get('PS_LANG_DEFAULT'),
                    'log_alert',
                    Mail::l('Log: You have a new alert from your shop', (int)Configuration::get('PS_LANG_DEFAULT')),
                    array(
                        '{log_message}' => $log->message),
                    $to
                );
            }

    }

    public static function logOnFile($log)
    {
        // DEBUG = 0;
        // INFO = 1;
        // WARNING = 2;
        // ERROR = 3;

        if ($log->severity >= 3)
            self::$klogger->LogError($log->message);
        else if ($log->severity == 2)
            self::$klogger->LogWarn($log->message);
        else if ($log->severity == 1)
            self::$klogger->LogInfo($log->message);
        else
            self::$klogger->LogDebug($log->message);
    }

    /**
     * add a log item to the database and send a mail if configured for this $severity
     *
     * @param string $message the log message
     * @param int $severity
     * @param int $error_code
     * @param string $object_type
     * @param int $object_id
     * @param boolean $allow_duplicate if set to true, can log several time the same information (not recommended)
     * @return boolean true if succeed
     */
    public static function addLog($message, $severity = 1, $error_code = null, $object_type = null, $object_id = null, $allow_duplicate = false)
    {
        $log = new Logger();
        $log->severity = intval($severity);
        $log->error_code = intval($error_code);
        if (_LOG_PERSIST_ON_DB_)
            $log->message = pSQL($message);
        else
            $log->message = $message;

        $log->date_add = date('Y-m-d H:i:s');
        $log->date_upd = date('Y-m-d H:i:s');
        if (!empty($object_type) && !empty($object_id)) {
            $log->object_type = pSQL($object_type);
            $log->object_id = intval($object_id);
        }

       // Logger::sendByMail($log);
       // if (_LOG_FILE_PATH_PRO_ != "")
            Logger::logOnFile($log);

      /*  if (_LOG_PERSIST_ON_DB_ && ($allow_duplicate || !$log->_isPresent())) {
            $res = $log->add();
            if ($res) {
                self::$is_present[$log->getHash()] = isset(self::$is_present[$log->getHash()]) ? self::$is_present[$log->getHash()] + 1 : 1;
                return true;
            }
        }*/
        return false;
    }

    /**
     * this function md5($this->message.$this->severity.$this->error_code.$this->object_type.$this->object_id)
     *
     * @return string hash
     */
    public function getHash()
    {
        if (empty($this->hash))
            $this->hash = md5($this->message . $this->severity . $this->error_code . $this->object_type . $this->object_id);

        return $this->hash;
    }


}

LoggerCore::init();


?>
