<?php

class Dhook
{
    // coding事件
    protected $event;
    // 发送的数据
    protected $postData;

    const REMOTE_PREFIX = 'refs/heads/';

    function __construct()
    {
        $this->event = $_SERVER['HTTP_X_CODING_EVENT'];
        $this->postData = json_decode(file_get_contents('php://input'), true);
    }

    public function validate()
    {
        $postData = $this->postData;
        if(!isset($postData['token'])||($postData['token']!=SECRET)){
            throw new Exception("Token不正确",ErrorRecord::SECRET_ERROR);
        }
        if (empty($this->event) || ($this->event != EVENT)) {
            throw new Exception("触发事件错误", ErrorRecord::EVENT_ERROR);
        }
        if (empty($this->postData)) {
            throw new Exception("POST错误", ErrorRecord::POST_ERROR);
        }
        if (!isset($postData['ref']) || $postData['ref'] != self::REMOTE_PREFIX . REMOTE) {
            throw new Exception("远程仓库错误", ErrorRecord::REMOTE_ERROR);
        }
    }

    public function createRepo()
    {
        $path = PROJECT_PATH;
        if (!is_dir($path)) {
            $command = 'mkdir ' . $path;
            shell_exec($command);
        }
        if (!is_dir($path . '/.git')) {
            $command = 'cd ' . $path . ' && sudo git init';
            shell_exec($command);
        }
        if (!file_exists(LOG_PATH)) {
            $command = 'touch ' . LOG_PATH;
            shell_exec($command);
        }
    }

    public function run()
    {
        $this->validate();
        $this->createRepo();
        $reset = 'cd ' . PROJECT_PATH . ' && sudo git reset --hard';
        $pull = 'cd ' . PROJECT_PATH . ' && sudo git pull ' . GIT_URL . ' ' . REMOTE . ':master 2>>' . LOG_PATH . ' 1>>' . LOG_PATH;
        shell_exec($reset);
        shell_exec($pull);
        $this->log();
    }

    public function log()
    {
        file_put_contents(LOG_PATH, "\n" . date("Y-m-d H:i:s") . "\n" . "--------finish---------" . "\n", FILE_APPEND);
    }

}
