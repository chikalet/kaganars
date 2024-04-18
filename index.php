<?php
    function getData($method)
    {
        $data = new stdClass();
        if ($method !="GET")
        {
            $data->body = json_decode(file_get_contents('php://input'));
        }
        $data->parameters = [];
            $dataGet = $_GET;
            foreach($dataGet as $key => $value)
            {
                if ($key != "q")
                {
                    $data->parameters[$key] = $value;
                }
            }
            return $data;
    }

    function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    header('Content-type: application/json');
    $link = mysqli_connect('mysql', 'root', 's123123', 'users');

    if (!$link)
    {
        echo "Ошибка. Невозможно установить соединение с БД".PHP_EOL;
        echo "Код ошибки errno: ".mysqli_connect_errno().PHP_EOL;
        echo "Текст ошибки error: ".mysqli_connect_error().PHP_EOL;
        exit();
    }
    $message = [];
    $message["users"] = [];
    $res = $link->query("SELECT id, name, login FROM users ORDER BY id ASC");
    if (!$res)
    {
        echo "не удалось выполнить запрос";
    }
    else
    {
        while($row = $res->fetch_assoc())
        {
            $message["users"][] = [
                "id" => $row['id'],
                "login" => $row['login'],
                "name" => $row['name'],
            ];
        }
    }
    $url = $_SERVER['REQUEST_URI'];
    $urlList = explode('/', $url);

    $router = $urlList[1];
    $requestData = getData(getMethod());
    $method = getMethod();

    if (file_exists(realpath(dirname(__FILE__)).'/routers/'.$router.'.php'))
    {
        include_once 'routers/'.$router.'.php';
        route($method, $urlList, $requestData);
    }
    else
    {
        echo 'NOPE 404';
    }

