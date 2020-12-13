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

use Pssh\Exception\PsftpException;

class Psftp
{
    /**
     * ssh resource
     *
     * @var resource
     */
    private Pssh $ssh;

    /**
     * sftp resource
     *
     * @var resource
     */
    private $sftp;

    /**
     * setup sftp
     *
     * @param Pssh $ssh
     */
    public function __construct(Pssh $ssh)
    {
        $this->ssh  = $ssh;
        $this->sftp = ssh2_sftp($ssh->getConnection());
    }

    /**
     * copy file to server 
     *
     * @param  string  $file
     * @param  string  $location
     * @param  integer $mode
     * @return boolean
     */
    public function copyToServer(string $file, string $location, int $mode = 0644): bool
    {
        if(!is_file($file))
            throw new PsftpException("$file is not a valid file.");
        
        return ssh2_scp_send($this->ssh->getConnection(), $file, $location, $mode);
    }

    /**
     * copy file from server 
     *
     * @param  string  $file remote file
     * @param  string  $location local location
     * @return boolean
     */
    public function copyFromServer(string $file, string $location): bool
    {
        return ssh2_scp_recv($this->ssh->getConnection(), $file, $location);
    }

    /**
     * change file mod
     *
     * @param  string $filename
     * @param  integer $mode
     * @return boolean
     */
    public function chmod(string $filename, int $mode): bool
    {
        return ssh2_sftp_chmod($this->sftp, $filename, $mode);
    }

    /**
     * create a directory
     *
     * @param  string $dirname
     * @param  integer $mode
     * @param  boolean $recursive
     * @return boolean
     */
    public function mkdir(string $dirname, int $mode = 0777, bool $recursive = FALSE): bool
    {
        return ssh2_sftp_mkdir($this->sftp, $dirname, $mode, $recursive);
    }

    /**
     * remove a directory
     *
     * @param  string $dirname
     * @return boolean
     */
    public function rmdir(string $dirname): bool
    {
        return ssh2_sftp_rmdir($this->sftp, $dirname);
    }

    /**
     * create a symlink
     *
     * @param string $symlink
     * @return bool
     */
    public function symlink(string $target, string $link): bool
    {
        return ssh2_sftp_symlink($this->sftp, $target, $link);
    }

    /**
     * read target symlink
     *
     * @param string $symlink
     * @return string
     */
    public function symlinkTarget(string $symlink): string
    {
        return ssh2_sftp_readlink($this->sftp, $symlink);
    }

    /**
     * stating a symlink
     *
     * @param string $symlink
     * @return array
     */
    public function symlinkStat(string $symlink): array
    {
        return ssh2_sftp_lstat($this->sftp, $symlink);
    }

    /**
     * realpath
     *
     * @param string $link
     * @return boolean
     */
    public function realpath(string $link): string
    {
        return ssh2_sftp_realpath($this->sftp, $link);
    }

    /**
     * rename file
     *
     * @param string $from
     * @param string $to
     * @return string
     */
    public function rename(string $from, string $to): bool
    {
        return ssh2_sftp_rename($this->sftp, $from, $to);
    }

    /**
     * file stat 
     *
     * @param  string $file
     * @return array
     */
    public function stat(string $file): array
    {
        return ssh2_sftp_stat($this->sftp, $file);
    }

    /**
     * delete file 
     *
     * @param  string $file
     * @return array
     */
    public function unlink(string $file): bool
    {
        return ssh2_sftp_unlink($this->sftp, $file);
    }
}