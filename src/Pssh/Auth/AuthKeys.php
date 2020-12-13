<?php namespace Pssh\Auth;

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

use Pssh\Exception\AuthKeysException;

class AuthKeys implements AuthInterface
{
    /**
     * user
     *
     * @var string
     */
    private string $user;

    /**
     * public key file name
     *
     * @var string
     */
    private string $pubKey;

    /**
     * private key file name
     *
     * @var string
     */
    private string $privKey;

    /**
     * passPhrase if any
     *
     * @var string
     */
    private ?string $passPhrase;

    /**
     * setup auth keys
     *
     * @param string $user
     * @param string $publicKey
     * @param string $privateKey
     * @param string $passPhrase
     */
    public function __construct(string $user, string $privKey, string $pubKey, ?string $passPhrase = NULL)
    {
        if(!is_file($privKey))
            throw new AuthKeysException("private key ($privKey) is not a valid file");

        if(!is_file($pubKey))
            throw new AuthKeysException("public key ($pubKey) is not a valid file");

        $this->user       = $user;
        $this->privKey    = $privKey;
        $this->pubKey     = $pubKey;
        $this->passPhrase = $passPhrase;
    }

    /**
     * authenticate using ssh keys
     * your public key should be added to authorized_keys file in your server
     *
     * @param  resource $conn
     * @return boolean
     */
    public function auth($conn): bool
    {
        if(!ssh2_auth_pubkey_file($conn, $this->user, $this->pubKey, $this->privKey, $this->passPhrase))
            throw new AuthKeysException("public key authentication failed");

        return TRUE;
    }
}