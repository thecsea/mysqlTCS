<?php
/**
 * Created by PhpStorm.
 * User: Claudio Cardinale
 * Date: 22/05/15
 * Time: 21.48
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

namespace it\thecsea\mysqltcs;
use it\thecsea\mysqltcs\connections\MysqlConnection;
use it\thecsea\mysqltcs\connections\MysqlConnectionException;
use it\thecsea\mysqltcs\connections\MysqlConnections;


/**
 * Class mysqltcs
 * @author Claudio Cardinale <cardi@thecsea.it>
 * @copyright 2015 ClaudioCardinale
 * @version 3.0.0-dev
 * @package it\thecsea\mysqltcs
 */
class Mysqltcs {

    /**
     * @var String
     */
    private $host;
    /**
     * @var String
     */
    private $user;
    /**
     * @var String
     */
    private $password;
    /**
     * @var String
     */
    private $name;
    /**
     * @var bool
     */
    private $newConnection;
    /**
     * @var string
     */
    private $key;
    /**
     * @var string
     */
    private $cert;
    /**
     * @var string
     */
    private $ca;
    /**
     * @var MysqlConnection
     */
    private $mysqlRef;

    /**
     * @var \mysqli
     */
    private $mysqliRef;

    /**
     * @var MysqlConnections
     */
    private $mysqlConnections;

    /**
     * @var mysqltcsLogger
     */
    private $logger = null;

    /**
     * Get a connection to mysql
     * @param String $host
     * @param String $user
     * @param String $password
     * @param String $name
     * @param bool $newConnection optional, default true. If it is false the class uses an already open connection if it possible
     * @param string $key optional
     * @param string $cert optional
     * @param string $ca optional
     */
    public function __construct($host, $user, $password, $name, $newConnection = true, $key = "", $cert = "", $ca = "")
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->name = $name;
        $this->newConnection = $newConnection;
        $this->key = $key;
        $this->cert = $cert;
        $this->ca = $ca;
        $this->mysqlConnections = MysqlConnections::getInstance();
        $this->getConnection();
    }

    /**
     * @throws MysqlConnectionException
     */
    public function __destruct()
    {
        if(!$this->newConnection)
            $this->mysqlConnections->removeClient($this);

    }

    /**
     * Set the logger, set null if you don't want to log
     * @param mysqltcsLogger $logger
     */
    public function setLogger(mysqltcsLogger $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param String $mex
     */
    private function log($mex)
    {
        if(!$this->logger)
            return ;
        $this->logger->log($mex);
    }

    /**
     * Get the connection according to newConnection value
     * @throws MysqlConnectionException
     */
    private function getConnection()
    {
        if($this->newConnection)
        {
            $this->mysqlRef = new MysqlConnection($this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
            $this->mysqlRef->connect();
        }
        else
        {
            $this->mysqlRef = $this->mysqlConnections->getConnection($this, $this->host, $this->user, $this->password, $this->name, $this->key, $this->cert, $this->ca);
        }
        $this->mysqliRef = $this->mysqlRef->getMysqli();
    }

    /**
     * Get if mysqltcs is connected (using mysqli::ping)
     * @return bool if true mysqltcs is connected
     */
    public function isConnected()
    {
        return $this->mysqlRef->getMysqli()->ping();
    }

    /**
     * Get the thread id (it can be used as mysqli identifier)
     * @return int
     */
    public function getConnectionThreadId()
    {
        return $this->mysqliRef->thread_id;
    }

    /**
     * Execute an sql query and log it
     * @param String $query sql query
     * @return bool|\mysqli_result mysql query return
     * @throws MysqltcsException thrown if an sql error is occurred, the message contain the mysql error and query
     */
    public function executeQuery($query)
    {
        $results = $this->mysqliRef->query($query);
        if(!$results) {
            $mex = "Mysql error " . $this->mysqliRef->error . " on '" . $query."''";
            $this->log($mex);
            throw new MysqltcsException($mex);
        }else {
            $this->log($query);
            return $results;
        }
    }
}