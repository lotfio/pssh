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

use Pssh\Exception\AuthUserPassException;

class AuthUserPass implements AuthInterface
{
    /**
     * user 
     *
     * @var string
     */
    private string $user;

    /**
     * password
     *
     * @var string
     */
    private string $passwd;

    /**
     * setup user & password
     *
     * @param string $user
     * @param string $passwd
     */
    public function __construct(string $user, string $passwd)
    {
        $this->user   = $user;
        $this->passwd = $passwd;
    }

    /**
     * authenticate with user & password
     *
     * @param  resource $conn
     * @return boolean
     */
    public function auth($conn): bool
    {
        if(!ssh2_auth_password($conn, $this->user, $this->passwd))
            throw new AuthUserPassException("wrong user or password");

        return TRUE;
    }
}