<?php

namespace Spyrit\Silex\Utils\Command;

use Symfony\Component\Console\Command\Command;

/**
 * Propel AbstractSqlCommand
 *
 * @author Charles Sanquer <charles.sanquer@spyrit.net>
 *
 */
abstract class PropelAbstractSqlCommand extends Command
{
    protected $connections;
    
    protected $default;

    protected function retrieveConnectionInfos()
    {
        if (empty($this->connections)) {
            $this->connections = array();
            $xml = simplexml_load_file($this->getApplication()->getAppDir().'/config/runtime-conf.xml');

            foreach($xml->propel->datasources->attributes() as $name => $value) {
                if ($name == 'default') {
                    $this->default = (string) $value;
                }
            }
            
            foreach ($xml->propel->datasources->datasource as $datasource) {
                $connection = array(
                    'dsn' => (string) $datasource->connection->dsn,
                    'user' => null,
                    'password' => null,
                );

                if (isset($datasource->adapter)) {
                    $connection['driver'] = (string) $datasource->adapter;
                }
                
                if (isset($datasource->connection->user)) {
                    $connection['user'] = (string) $datasource->connection->user;
                }

                if (isset($datasource->connection->password)) {
                    $connection['password'] = (string) $datasource->connection->password;
                }
                $this->connections[(string) $datasource->attributes()->id] = $connection;
            }
        }
    }
    
    protected function getPdo($infos)
    {
        switch ($infos['driver']) {
            case 'sqlite':
            case 'postgre':
                return  new \PDO($infos['dsn']);
                break;
            case 'mysql':
            default:
                return  new \PDO($infos['dsn'], $infos['user'], $infos['password']);
                break;
        };
    }
    
    public function getConnectionInfos($name = null)
    {
        $this->retrieveConnectionInfos();

        if (empty($name))
        {
            return $this->connections;
        } else {
            return isset($this->connections[$name]) && !empty($this->connections[$name]['dsn']) ? $this->connections[$name] : null;
        }
    }
    
    public function getDefaultConnectionName()
    {
        $this->retrieveConnectionInfos();
        return $this->default;
    }
    
    public function getDefaultConnectionInfos()
    {
        return $this->getConnectionInfos($this->getDefaultConnectionName());
    }
    
    /**
     * 
     * @return array of \PDO
     */
    public function getConnections()
    {
        $pdos = array();
        
        $connectionInfos = $this->getConnectionInfos();
        foreach ($connectionInfos as $name => $infos) {
            $pdos[$name] = $this->getPdo($infos);
        }
        
        return $pdos;
    }
    
    /**
     * 
     * @param string $name
     * @return \PDO
     */
    public function getConnection($name)
    {
        return $this->getPdo($this->getConnectionInfos($name));
    }
    
    /**
     * 
     * @return \PDO
     */
    public function getDefaultConnection()
    {
        return $this->getPdo($this->getDefaultConnectionInfos());
    }
}
