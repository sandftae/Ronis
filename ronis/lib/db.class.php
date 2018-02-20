<?php

/*
 * Класс, который позволяет проверять наличие пользователя/почтового адреса в БД или создавать
 * нового пользователя. Содержит подключени к БД через PDO.
 * "Исполянется" данный класс в классе Арр
 */
Class Database{
    public $connection;
    public function __construct()
    {

        /*
         * Соединение с БД
         */
        try {
            $database = new PDO("mysql:host=" . HOST . ";dbname=" . DB . ";charset=utf8", USER, PASS);
            $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->connection = $database;
        }catch(Exception $e){
            echo "Соединение с базой данных не проведено. Причина:" . $e -> getMessage();
            exit();
        }
    }



    /*
     * ДАнный метод вносит в БД и файловую директорию новую картинку
     * и данные по ней
     */

    public function insertSrcImg(array $data , $srcInDir){
        $sql = 'INSERT INTO ' . TABLE_BANNERS . ' VALUES(null , ? , ? , ? , ?)';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute([$data['name_banner'] , $srcInDir ,
                                    $data['url_banner'] ,  $data['status_banner']]);
        return $result;
    }


/*
 * Метод, который вытаскивает все записи из таблицы banners
 */
    public function getAllSrc(){
        $sql = 'SELECT * FROM ' . TABLE_BANNERS ;
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /*
     * Данный метод возвращает все инфо по полученному id
     */
    public function getImgById($number){
        $sql = 'SELECT * FROM ' . TABLE_BANNERS . ' WHERE id='.$number;
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /*
     * Метод позволяет провести обновление таблицы banners по заданному id. Обязательно
     * вместе с id корректируемой записи мептоду нужно передать массив с данными ($_POST) с формы
     * и новый адресс хранения картинки на сервере (в моем случае в файловой системе). Если что-то
     * не меняется, то отправляются старые данные, которые были
     */

    public function updateImgInfo($id , array $data ,  $srcInDir){
        $sql = 'UPDATE '. TABLE_BANNERS .' SET name="'. $data['name_banner'] .'" ,  file_src="' . $srcInDir . '" ,
                    url="' . $data['url_banner'] . '" ,  status="' . $data['status_banner'] . '" WHERE id="' . $id . '"';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }


    /*
     * Данный метод возвращает нумерованный массив из таблицы "position". В данной таблице будет
     * создаваться связь между id каждого отдельного баннера в таблице banners и его позицией в списке
     * отображения и списке "прокрутке" через показ баннеров.
     */

    public function getAllPosition(){
        $sql = 'SELECT * FROM ' . TABLE_POSITION;
        $query =$this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /*
     * Данный метод возвращает определенную запись из таблицы position по переданному id
     */

    public function getPositionById($id_banner){
        $sql = 'SELECT * FROM ' . TABLE_POSITION . ' WHERE id_banner="' . $id_banner . '"';
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }


    /*
     * Метод вносит значение позиции в таблицу position
     */

    public function insertPosition($position_number){
        $sql = 'INSERT INTO ' . TABLE_POSITION . ' VALUES(null , ?)';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute([$position_number]);
        return $result;
    }

    /*
     * Метод проводит обновление позиции по заданному id
     *
     */

    public function updatePositionById($id_banner , $new_position_number){
        $sql = 'UPDATE ' . TABLE_POSITION . ' SET number_position="'.$new_position_number.'" WHERE id_banner="'.$id_banner.'"';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }


    /*
     * Данный метод позволяет вносить комплексно новые данные в обновляемую таблицу.
     * массив $result содетжит результат по каждой вносимой записи. Этот массив позволит провести
     * более оперативно отладку, если она понадоится.
     */
    public function updateAllPosition(array $data){
        $result = [];
        foreach($data as $key => $value){
            $sql = 'UPDATE ' . TABLE_POSITION . ' SET number_position="'.$value.'" WHERE id_banner="'.$key.'"';
            $conn = $this -> connection -> prepare($sql);
            $result[] = $conn -> execute();
        }
        // Массив для проведения отладки. Собержит инфо по всем внесенным данным (TRUE/FALSE)
        return $result;
    }

//------------------------






//==============================
/*
 * Ниже описываются методы БД, которые нужны будут, когда на
 * сайте разрешено будет регистрироваться пользваотелям самостоятельно.
 * Здесь есть проверка ена существование поьзователя с таким логином
 * или почтой и проверка совпадений логина и имейла для входа.
 * Написал, т.к. пларнировал зделать больше, чем нужно было по ТЗ
 */
//==============================



//------------------------

//
//    /*
//     * Метод, который позволяет получить тот или иной ответ на запрос
//     */
//
//    public function query($sql)
//    {
//        $query = $this -> connection -> query($sql);
//        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
//    /*
//     * Метод, который позволяет создавать пользователя
//     */
//    public function create_user(array $data)
//    {
//        $sql = "INSERT INTO user VALUES(null , ? , ? , ?);";
//        $conn = $this -> connection -> prepare($sql);
//        $result = $conn -> execute( [$data['login'] , $data['email'] , $data['password']] );
//        return $result;
//    }
//
//    /*
//     * Метод для проверки существования логина. Использвуется при валидации
//     */
//    public function cheсk_login($login)
//    {
//        $sql = "SELECT * FROM  " . TABLE_NAME . "  WHERE login='{$login}'";
//        $sth = $this ->connection -> query($sql);
//        $result = $sth -> fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
//
//
//    /*
//     * Метод для проверки существования email. Использвуется при валидации
//     */
//    public function cheсk_email($email)
//    {
//        $sql = "SELECT * FROM  " . TABLE_NAME . "  WHERE email='{$email}'";
//        $sth = $this ->connection -> query($sql);
//        $result = $sth -> fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
//
//    /*
//     * Метод для проверки был-ли зарегистрирован пользователь ранее.
//     *  Использвуется при валидации
//     */
//    public function check_user($password , $login){
//        $sql = "SELECT * FROM " . TABLE_NAME . " WHERE password='{$password}' AND login='{$login}'";
//        $sth = $this -> connection -> query($sql);
//        $result = $sth -> fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
//
//
//    /*
//     * Метод для получения ID-пользвателя. Использвуется при внесении ID-пользователя
//     * к каждому оставленному им комменту или отправленному сообщению
//     */
//    public function getUserId($data)
//    {
//        $sql = "SELECT id_user FROM " . TABLE_NAME . " WHERE login='{$data['name']}' ";
//        $sth = $this -> connection -> query($sql);
//        $result = $sth -> fetchAll(PDO::FETCH_ASSOC);
//        return $result;
//    }
}











