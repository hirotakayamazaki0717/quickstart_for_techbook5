<?php
// パスの結合へルパ
function join_paths(array $paths)
{
    return implode(DIRECTORY_SEPARATOR, $paths);
}

define('APP_ROOT', dirname(__FILE__));
define('LIB_ROOT', join_paths([APP_ROOT, 'lib']));
define('MODELS_ROOT', join_paths([APP_ROOT, 'models']));

require_once join_paths([APP_ROOT, 'config', 'env.php']);
require_once join_paths([LIB_ROOT, 'functions.php']);

// DB
function db()
{
    static $conn;

    if (!isset($conn)) {
        $db       = DB_DBNAME;
        $host     = DB_HOSTNAME;
        $username = DB_USERNAME;
        $password = DB_PASSWORD;

        try {
            $conn = new PDO("mysql:dbname=$db;host=$host", $username, $password);
            $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo 'データベースに接続できません！アプリの設定を確認してください。';
            exit;
        }
    }

    return $conn;
}

// Session
require_once join_paths([LIB_ROOT, 'Session.php']);

function session($namespace = 'app')
{
    static $sessions;

    if (!isset($sessions[$namespace])) {
        $sessions[$namespace] = new Session($namespace);
    }

    return $sessions[$namespace];
}

function csrf_field(Session $session)
{
    $name  = $session->getRequestCsrfTokenKey();
    $token = $session->getCsrfToken();
    echo '<input type="hidden" name="'.$name.'" value="'.h($token).'">';
}
