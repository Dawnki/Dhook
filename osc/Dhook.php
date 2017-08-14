<?php
/**
 * Created by PhpStorm.
 * User: dawnki
 * Date: 17-8-12
 * Time: 上午1:12
 */

class Dhook
{
    protected $postData;

    const REMOTE_PREFIX = 'refs/heads/';

    function __construct()
    {
        $this->postData = json_decode(file_get_contents('php://input'), true);
    }

    public function validate()
    {
        $postData = $this->postData;
        if (!isset($postData['password']) || ($postData['password'] != SECRET)) {
            throw new Exception("Password不正确");
        }
        if (empty($this->postData)) {
            throw new Exception("POST错误");
        }
        if (!isset($postData['ref']) || $postData['ref'] != self::REMOTE_PREFIX . REMOTE) {
            throw new Exception("远程仓库错误");
        }
    }

    public function createRepo()
    {
        $path = PROJECT_PATH;
        if (!is_dir($path)) {
            $command = 'mkdir ' . $path . ' 2>>'.LOG_PATH. ' 1>>' . LOG_PATH;
            shell_exec($command);
        }
        if (!is_dir($path . '/.git')) {
            $command = 'cd ' . $path . ' && git init'. ' 2>>'.LOG_PATH. ' 1>>' . LOG_PATH;
            shell_exec($command);
        }
        if (!file_exists(LOG_PATH)) {
            $command = 'touch ' . LOG_PATH . ' 2>>'.LOG_PATH. ' 1>>' . LOG_PATH;
            shell_exec($command);
        }
    }

    public function pullData()
    {
        $reset = 'cd ' . PROJECT_PATH . ' && sudo git reset --hard'. ' 2>>'.LOG_PATH;
        $pull = 'cd ' . PROJECT_PATH . ' && sudo git pull ' . GIT_URL . ' ' . REMOTE . ':master 2>>' . LOG_PATH . ' 1>>' . LOG_PATH;
        shell_exec($reset);
        shell_exec($pull);
    }

    public function log()
    {
        file_put_contents(LOG_PATH, "\n" . date("Y-m-d H:i:s") . "\n" . "--------finish---------" . "\n", FILE_APPEND);
    }

    public function run()
    {
        $this->validate();
        $this->createRepo();
        $this->pullData();
        $this->log();
    }
}