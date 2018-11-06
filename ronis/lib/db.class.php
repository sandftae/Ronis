<?php

/**
 * Класс, который позволяет проверять наличие пользователя/почтового адреса в БД или создавать
 * нового пользователя. Содержит подключени к БД через PDO.
 * "Исполянется" данный класс в классе Арр
 *
 * Class Database
 */
Class Database
{
    /**
     * @var PDO
     */
    public $connection;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        try {
            $database = new PDO("mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8", USER, PASS);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection = $database;
        } catch (Exception $e) {
            echo "Соединение с базой данных не проведено. Причина:" . $e->getMessage();
            exit();
        }
    }

    /**
     * Метод вносит в БД и файловую директорию новую картинку
     * и данные по ней
     *
     * @param array $data
     * @param $srcInDir
     * @return bool
     */
    public function insertSrcImg(array $data, $srcInDir)
    {
        $sql = 'INSERT INTO ' . TABLE_BANNERS . ' VALUES(null , ? , ? , ? , ?)';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute([$data['name_banner'], $srcInDir,
            $data['url_banner'], $data['status_banner']]);
        return $result;
    }

    /**
     * Метод, который вытаскивает все записи из таблицы banners
     *
     * @return array
     */
    public function getAllSrc()
    {
        $sql = 'SELECT * FROM ' . TABLE_BANNERS;
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Данный метод возвращает все инфо по полученному id
     * ы
     * @param $number
     * @return array
     */
    public function getImgById($number)
    {
        $sql = 'SELECT * FROM ' . TABLE_BANNERS . ' WHERE id=' . $number;
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Метод позволяет провести обновление таблицы banners по заданному id.
     *
     * @param $id
     * @param array $data
     * @param $srcInDir
     * @return bool
     */
    public function updateImgInfo($id, array $data, $srcInDir)
    {
        $sql = 'UPDATE ' . TABLE_BANNERS . ' SET name="' . $data['name_banner'] . '" ,  file_src="' . $srcInDir . '" ,
                    url="' . $data['url_banner'] . '" ,  status="' . $data['status_banner'] . '" WHERE id="' . $id . '"';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * Данный метод возвращает нумерованный массив из таблицы "position".
     *
     * @return array
     */
    public function getAllPosition()
    {
        $sql = 'SELECT * FROM ' . TABLE_POSITION;
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param $id_banner
     * @return array
     */
    public function getPositionById($id_banner)
    {
        $sql = 'SELECT * FROM ' . TABLE_POSITION . ' WHERE id_banner="' . $id_banner . '"';
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param $position_number
     * @return bool
     */
    public function insertPosition($position_number)
    {
        $sql = 'INSERT INTO ' . TABLE_POSITION . ' VALUES(null , ?)';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute([$position_number]);
        return $result;
    }

    /**
     * @param $id_banner
     * @param $new_position_number
     * @return bool
     */
    public function updatePositionById($id_banner, $new_position_number)
    {
        $sql = 'UPDATE ' . TABLE_POSITION . ' SET number_position="' . $new_position_number . '" WHERE id_banner="' . $id_banner . '"';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    public function updateAllPosition(array $data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $sql = 'UPDATE ' . TABLE_POSITION . ' SET number_position="' . $value . '" WHERE id_banner="' . $key . '"';
            $conn = $this->connection->prepare($sql);
            $result[] = $conn->execute();
        }
        return $result;
    }
}
