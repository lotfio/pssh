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

use Pssh\Exception\AuthHostBasedFileException;

class AuthHostBasedFile implements AuthInterface
{
    /**
     * username 
     *
     * @var string
     */
    private string $username;

    /**
     * host name
     *
     * @var string
     */
    private string $hostname;

    /**
     * host name
     *
     * @var string
     */
    private string $pubKey;

        /**
     * host name
     *
     * @var string
     */
    private string $privKey;

    /**
     * pass phrase
     *
     * @var string
     */
    private ?string $passPhrase;

    /**
     * local user name
     *
     * @var string
     */
    private ?string $localUsername;

    /**
     * setup auth host based file
     *
     * @param string $username
     * @param string $hostname
     * @param string $pubKey
     * @param string $privKey
     * @param string $passPhrase
     * @param string $localUsername
     */
    public function __construct(string $username, string $hostname, string $pubKey, string $privKey, ?string $passPhrase = NULL, ?string $localUsername = NULL)
    {
        if(!is_file($pubKey))
            throw new AuthHostBasedFileException("public key ($pubKey) is not a valid file");

        if(!is_file($privKey))
            throw new AuthHostBasedFileException("private key ($privKey) is not a valid file");

        $this->username      = $username;
        $this->hostname      = $hostname;
        $this->pubKey        = $pubKey;
        $this->privKey       = $privKey;
        $this->passPhrase    = $passPhrase;
        $this->localUsername = $localUsername;
    }

    /**
     * authenticate with host based file
     *
     * @param  resource $conn
     * @return boolean
     */
    public function auth($conn): bool
    {
        if(!ssh2_auth_hostbased_file($conn, $this->username, $this->hostname, $this->pubKey, $this->privKey, $this->passPhrase, $this->localUsername))
            throw new AuthHostBasedFileException("host based authentication failed");

        return TRUE;
    }
}