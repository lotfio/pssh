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

use Pssh\Exception\AuthNoneException;

class AuthNone implements AuthInterface
{
    /**
     * user 
     *
     * @var string
     */
    private string $user;

    /**
     * setup user
     *
     * @param string $passwd
     */
    public function __construct(string $user)
    {
        $this->user   = $user;
    }

    /**
     * get available authentication methods
     *
     * @param  resource $conn
     * @return boolean
     */
    public function auth($conn): bool
    {
        $none = ssh2_auth_none($conn, $this->user);
        throw new AuthNoneException("authentication failed, available auth methods " . implode(', ', $none));
        return FALSE;
    }
}