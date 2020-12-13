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

use Pssh\Exception\AuthAgentException;

class AuthAgent implements AuthInterface
{
    /**
     * user 
     *
     * @var string
     */
    private string $user;

    /**
     * setup user agent
     *
     * @param string $passwd
     */
    public function __construct(string $user)
    {
        $this->user   = $user;
    }

    /**
     * authenticate with user agent
     *
     * @param  resource $conn
     * @return boolean
     */
    public function auth($conn): bool
    {
        if(!ssh2_auth_agent($conn, $this->user))
            throw new AuthAgentException("wrong user or public key not authorized");

        return TRUE;
    }
}