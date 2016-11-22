<?php

namespace ahmetertem;

class database
{
    public $PDO = null;
    //public $memorize_queries = false;
    // public $memorize_prepares = false;
    public $errors = array();
    private $_query_count = 0;

    public function __construct($dsn, $user = null, $password = null, $arguments = null)
    {
        $this->PDO = new \PDO($dsn, $user, $password, $arguments);
    }

    public function query()
    {
        ++$this->_query_count;
        $result = call_user_func_array(array($this->PDO, 'query'), func_get_args());

        return $result;
    }

    public function fetchColumn($query_string, $args = array())
    {
        $sth = $this->prepare($query_string);
        $sth->execute($args);

        return $sth->fetchColumn();
    }

    public function exec($query_string)
    {
        ++$this->_query_count;
        $result = $this->PDO->exec($query_string);
        if (intval($this->PDO->errorCode()) != 0) {
            $this->errors[] = array('query_string' => $query_string, 'error_info' => $this->PDO->errorInfo());
        }

        return $result;
    }

    public function prepare($query_string, $arguments = array())
    {
        ++$this->_query_count;

        return $this->PDO->prepare($query_string, $arguments);
    }

    public function errorInfo()
    {
        return $this->PDO->errorInfo();
    }

    public function lastInsertId()
    {
        return $this->PDO->lastInsertId();
    }

    public function getQueryCount()
    {
        return $this->_query_count;
    }
}
