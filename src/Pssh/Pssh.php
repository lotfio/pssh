<?php namespace Pssh;

/*
 * This file is a part of Pssh package
 *
 * @package     Pssh
 * @version     0.1.0
 * @author      Lotfio Lakehal <contact@lotfio.net>
 * @copyright   Lotfio Lakehal 2020
 * @license     MIT
 * @link        https://github.com/lotfio/pssh
 *
 */

use Pssh\Exception\PsshException;
use Pssh\Auth\AuthInterface;

class Pssh
{
    /**
     * Pssh extra methods
     */
    use PsshTrait;

    /**
     * ssh host
     *
     * @var string
     */
    private string $host;

    /**
     * ssh port
     *
     * @var int
     */
    private int $port;

    /**
     * ssh ping timeout
     *
     * @var string
     */
    private float $timeout;

    /**
     * connection methods
     */
    private array $methods;

    /**
     * connection callbacks
     */
    private array $callbacks;

    /**
     * ssh connection instance
     *
     * @var resource
     */
    private $conn;

    /**
     * auth object
     *
     * @var AuthInterface
     */
    private AuthInterface $auth;

    /**
     * setup ssh config & connect
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if(!\extension_loaded('ssh2')) 
            throw new PsshException("ssh2 extension is required for Pssh");

        if(!array_key_exists(strtolower('host'), $config)) 
            throw new PsshException("ssh host is required");

        if(!array_key_exists(strtolower('auth'), $config)) 
            throw new PsshException("ssh auth is required");

        if(!$config['auth'] instanceof AuthInterface)
            throw new PsshException("auth should be an instance of AuthUserPass, AuthKeys, AuthHostBasedFile, AuthAgent or AuthNone.");

        if(isset($config['methods']) && !is_array($config['methods']))
            throw new PsshException("connection methods must be an array");

        if(isset($config['callbacks']) && !is_array($config['callbacks']))
            throw new PsshException("connection callbacks must be an array");  

        // set up connection credentials
        $this->host      = $config['host'];
        $this->auth      = $config['auth'];
        $this->port      = $config['port']      ?? 22;
        $this->timeout   = $config['timeout']   ?? 1;
        $this->methods   = $config['methods']   ?? [];
        $this->callbacks = $config['callbacks'] ?? [];

        // establish ssh connection
        $this->connect();
    }

    /**
     * ping host
     *
     * @return boolean
     */
    private function ping(): bool
    {
        $check = @fsockopen($this->host, $this->port, $errno, $errstr, $this->timeout);
        if($check)
            return fclose($check);
        
        throw new PsshException($errstr, $errno);
    }

    /**
     * establish ssh connection
     *
     * @return boolean
     */
    private function connect(): bool
    {
        // check if host & port are reachable
        $this->ping();

        // connect 
        if (!($this->conn = ssh2_connect($this->host, $this->port, $this->methods, $this->callbacks)))
            throw new PsshException('cannot connect to server');

        // authenticate
        return $this->auth->auth($this->conn);
    }

    /**
     * ssh output
     *
     * @param  resource $stream
     * @param  integer  $std
     * @param  bool     $blocking
     * @return void
     */
    private function output($stream, int $std = SSH2_STREAM_STDIO, $blocking = true): void
    {
        stream_set_blocking($stream, $blocking);
        $output = ssh2_fetch_stream($stream, $std);

        $delimiter = ($std == SSH2_STREAM_STDIO) ? 'stdout' : 'stderr';
        echo "--$delimiter--\n";
        do{ echo trim(fread($output, 4095)) . "\n"; }while((!feof($output) && (strlen(fread($output, 4095)) > 0)));
        echo "--end-$delimiter--\n";
    }

    /**
     * execute ssh command
     *
     * @param  string $command
     * @return void
     */
    public function exec(string $command): void
    {
        if(!($stream = ssh2_exec($this->conn, $command)))
            throw new PsshException('SSH command failed');

        $this->output($stream);
        $this->output($stream, SSH2_STREAM_STDERR);
        fclose($stream);
    }

    /**
     * get connection resource
     *
     * @return void
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * close ssh connection
     */
    public function __destruct()
    {
        ssh2_disconnect($this->conn);
    }
}