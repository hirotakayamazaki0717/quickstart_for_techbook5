<?php

class Session
{
    protected $bag;

    public function __construct($namespace = 'app')
    {
        if (!session_id()) {
            session_start();
        }

        $this->bag = &$_SESSION[$namespace];

        if (!isset($this->bag)) {
            $this->bag[$this->getAppDataKey()]   = [];
            if (!$this->getCsrfToken()) {
                $this->bag[$this->getCsrfTokenKey()] = $this->generateCsrfToken();
            }
        }
    }

    public function getAppDataKey()
    {
        return 'app_data';
    }

    public function getCsrfTokenKey()
    {
        return 'csrf_token';
    }

    public function getRequestCsrfTokenKey()
    {
        return '__csrf_token';
    }

    public function generateCsrfToken()
    {
        return sha1(uniqid(rand(), true));
    }

    public function getCsrfToken()
    {
        return array_get($this->bag, $this->getCsrfTokenKey());
    }

    public function verifyCsrfToken()
    {
        $request_token = request_get($this->getRequestCsrfTokenKey());
        $valid_token   = $this->getCsrfToken();

        return $request_token === $valid_token;
    }

    public function get($key, $default = null)
    {
        return array_get($this->bag[$this->getAppDataKey()], $key, $default);
    }

    public function set($key, $value)
    {
        return $this->bag[$this->getAppDataKey()][$key] = $value;
    }

    public function unset($key)
    {
        unset($this->bag[$this->getAppDataKey()][$key]);
    }

    public function unsetAll()
    {
        $this->bag[$this->getAppDataKey()] = [];
    }

    public function flash($key, $default)
    {
        $value = $this->get($key, $default);
        $this->unset($key);

        return $value;
    }
}
