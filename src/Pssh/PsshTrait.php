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

trait PsshTrait
{
    /**
     * get ssh connection finger print
     *
     * @param  integer $type
     * @return string
     */
    public function fingerprint(int $type = SSH2_FINGERPRINT_SHA1 | SSH2_FINGERPRINT_MD5): string
    {
        return ssh2_fingerprint($this->conn, $type);
    }

    /**
     * methods negotiated in this connection
     *
     * @return array
     */
    public function methodsNegotiated(): array
    {
        return ssh2_methods_negotiated($this->conn);
    }

    /**
     * open a tunnel
     *
     * @param  string  $host
     * @param  integer $port
     * @return resource
     */
    public function tunnel(string $host, int $port)
    {
        return ssh2_tunnel($this->conn, $host, $port);
    }

    /**
     * init pubkey
     *
     * @return resource|false
     */
    public function initPublicKey()
    {
        $init = ssh2_publickey_init($this->conn);
        if($init === FALSE)
            throw new PsshException("remote server does not support the publickey subsystem");

        return $init;
    }

    /**
     * add public a key
     *
     * @param string  $algo
     * @param string  $blob
     * @param array   $attributes
     * @param boolean $overwrite
     * @return boolean
     */
    public function addPublicKey(string $algo, string $blob, array $attributes, bool $overwrite = FALSE): bool
    {
        $pkey = $this->initPublicKey();
        return ssh2_publickey_add($pkey, $algo, $blob, $overwrite, $attributes);
    } 

    /**
     * remove a public key
     *
     * @param string $algo
     * @param string $blob
     * @return boolean
     */
    public function removePublicKey(string $algo , string $blob): bool
    {
        $pkey = $this->initPublicKey();
        return ssh2_publickey_remove($pkey, $algo, $blob);
    } 

    /**
     * list public keys
     *
     * @return array
     */
    public function listPublicKeys(): array
    {
        $pkey = $this->initPublicKey();
        return ssh2_publickey_list($pkey);
    } 
}