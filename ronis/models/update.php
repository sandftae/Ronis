<?php
/**
 * Created by PhpStorm.
 * User: 111
 * Date: 13.02.2018
 * Time: 0:09
 */
/*
 * Этот клас "разбит" на двке части:
 * 1) логика обработки данных от пользователя (валидация и т.п.)
 * 2) вспомогательные методы (запросы к БД)
 * Также посчитал нужным не выносить меитоды для обработки изображений, а оставить
 * здесь.
 */

class Update extends Model{

    //============================================
    /*
     * Здесь идет часть, которая отвечает за логику обработки данных полученных от
     * пользвателя.  Равзбивать на несколько классов было неудобно
     */
    //============================================

    //==============
    // Свойства для работы с директориями
    //==============


    protected $max_file_size = MAX_LOADING_FILE_SIZE;
    public $sql;
//    protected $upload_dir = PATH_FOR_IMG;
    public $masImgSrc;
    public $full_dir_img;
//    public $path_to_img_for_slider = PATH_TO_IMG_FOR_SLIDER;

    protected $lastRandomNumber = [];


    //=============
    // Свойтсва для работы с ошибками и полями
    //=============

    protected $upload_errors = [
        UPLOAD_ERR_OK               => "No errros" ,
        UPLOAD_ERR_INI_SIZE         => "Larger than upload_max_filesize",
        UPLOAD_ERR_FORM_SIZE        => "Larger than form MAX_FILE_SIZE",
        UPLOAD_ERR_PARTIAL          => "Partial upload",
        UPLOAD_ERR_NO_TMP_DIR       => "No temporary directory",
        UPLOAD_ERR_CANT_WRITE       => "Can`t write to disk",
        UPLOAD_ERR_EXTENSION        => "File upload stopped by extension",
        UPLOAD_ERR_NO_FILE          => "No file"
    ];

    public $file_error = [];


    //==============
    // Свойства, для работы с изображением
    //==============

    protected $width = MAX_WIDTH;
    protected $height = MAX_HEIGHT;
    public $filePath;


    public $image_info;
    public $image_create;

    public $new_width;
    public $new_height;

    public $path = PATH_FOR_IMG;


    //============
    /*
     * Этот массив есть результирующим массивовм для внесение его в
     * в таблицу POSITION. Он необходим, т.к. важно сохранить порядок
     * (по возврастанию) значения поля id_position в таблице POSITION.
     * Из нее (таблицы) будут браться данные для отображения во FlexSlider.
     * Его заполнение происходит здесь - massiveForFlexSlider
     */
    //============
    protected  $endMass = [];
    //============


    /*
     * Метод, который перемещает загружаемый файл с временной директории в место постоянного хранения.
     * Также метод проверяет загружен-ли файл на серрвер и проводит валидацию на заполнение полей.
     * Метод собирает ошибки, которые могут возникнуть при работе пользователя с формой и в дальнейшем
     * их отображает.
     * Проверка формата получаемых файлов также осуществляется здесь.
     * ВАЖНО!
     * Предполагается, что директория для хранения файлов имеется. По сему
     * не проводится проверка на наличие директории для хранения файлов
     */
    public function insertInDir(array $file, array $data)
    {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            move_uploaded_file($file['file']['tmp_name'], ROOT.$this->path . $file['file']['name']);
            $this->insertSrcImg($data, PATH_TO_IMG_FOR_SLIDER.$file['file']['name']);
            return true;

        } else {
            $this->file_error[] = 'have error in validation! hack?';
            unset($file);
            return false;
        }
    }


    /*
     * Валидация загружаемого изображения для баннера
     */
    public function validFiles(array $file)
    {
        $this->full_dir_img = $this->path . $file['file']['name'];

        if (empty($file['file'])) {
            $this->file_error[] = 'no file!';
            return false;
        }

        if ($file['file']['error'] > 0) {
            switch ($file['file']['error']) {
                case 1:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_INI_SIZE];
                    break;
                case 2:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_FORM_SIZE];
                    break;
                case 3:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_PARTIAL];
                    break;
                case 4:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_NO_TMP_DIR];
                    break;
                case 6:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_CANT_WRITE];
                    break;
                case 7:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_EXTENSION];
                    break;
                case 8:
                    $this->file_error[] = $this -> upload_errors[UPLOAD_ERR_NO_FILE];
                    break;
            }
            return false;
        }

        /*
         * Простая проверка вносимых типов данных. Из ТЗ ничего сложного
         * не требовалось
         */
        if ($file['file']['type'] != 'image/gif'
            && $file['file']['type'] != 'image/png'
            && $file['file']['type'] != 'image/jpg'
            && $file['file']['type'] != 'image/jpeg'
        ) {
            $this->file_error[] = 'invalid image format';
            return false;
        }


        /*
         * Проверка вносимого размера. в ТЗ не указывалось.
         * Поставил ограничение в 5 мб.
         */
        if ($file['file']['size'] > $this->max_file_size) {
            $this->file_error[] = 'the maximum size is 5 mb';
            return false;

        }
        return true;
    }


    /*
     * Валидация заполняемых полей и проверка на пустоту.
     * Сбор возможных ошибок
     */
    public function validField($data)
    {
        if(empty($data['name'])){
            $this->file_error['error_fields']['name_error'] = 'field is empty';

        }
        if(empty($data['url'])){
            $this->file_error['error_fields']['url_error'] = 'field is empty';
        }
        if(empty($data['status'])){
            $this->file_error['error_fields']['status_error'] = 'field is empty';
        }
        if(empty($data['position'])){
            $this->file_error['error_fields']['position_error'] = 'field is empty';
        }

        if ($data['position'] < 0 && !is_numeric($data['position'])) {
            $this->file_error['error_fields']['position_error'] = 'the value of the "position" field is less than zero or not the number';
        }

        if(count($this -> file_error['error_fields']) > 0){
            return false;
        }
        return true;
    }


    /*
     * Возвращает массив с данными по всем изображениям
     */

    public function getImgArr()
    {
        $this->masImgSrc = $this->getAllSrc();
    }

    public function getErrors(){
        return $this -> file_error;
    }

    public function getDir(){
        return $this -> full_dir_img;
    }

    /*
     * В этом методе происходит перебор значений в таблице position c созданием нового массива,
     * в котором буцдет соблюден порядок значений number_position, т.е. предотвращено дублирование.
     * Этот массив, в дальнейшем, будет внесен в таблицу position со всеми изменениеями.
     * для работы макссива нужно два обязательных параметра: 1) id изменяемой записи 2) новое
     * значение изменяемой записи.
     * Причип работы просто:
     * 1) определяется наличие "соседа" с текущим значеним (id + 1), и его соседа, и его соседа...
     * если такие есть - меняется их значкение для вывода на + 1;
     * 2) определяется наличие "соседа" с текущим значеним (id - 1), и его соседа, и его соседа...
     * если такие есть - меняется их значкение для вывода на - 1;
     * 3) проверяется не вышел-ли цикл за рамки минимального значения и если да - сохранить
     * текущее значение не изменным, т.к. дальше метод сам будет проверять возможность доублирования
     * значений;
     * 4) проверка дублирования значений. Если в основном массиве есть задулированные значения, то
     * создается вспомогательный массив.
     * 5) если вспомогательный массив пустой - метод прекращает выполняться;
     * 6) если вспомогательный массив не пустой - начинает ввполняться вспомогательный метод arrayBridge,
     * который ищет поля с одинаковым значение и переносит их в конец массива с изменением значениея.
     * предполагается, что если есть дублирование, то пользватель или ошибся, или нужно отобразить последнее
     * внесенное значение с изменение текущего. В каком-то смылсе метод пытается угадать, как нужно
     * подготовить массив, для внесения изменений в БД.
     * После всего возвращается новы массив, который вносится в БД, в дальнешем
     */

    public function setNewNumber($id_banner, $new_number)
    {
        $arr_all = $this->getAllPosition();
        $arr_banner = [];
        $arr_search = null;

        foreach ($arr_all as $keys => $values) {
            foreach ($values as $key => $value) {
                if ($key == 'id_banner') {
                    $arr_banner[$values[$key]] = $values['number_position'];
                }
            }
        }


        foreach ($arr_banner as $key => $value) {

            if ($key == $id_banner) {
                $arr_banner[$key] = $new_number;

                if($arr_banner[$key] == $arr_banner[$key + 1]){
                    for ($i = 1; $i <= (count($arr_banner) - $key); $i++) {
                        $arr_banner[$key + $i] += 1;
                    }
                }
                if($arr_banner[$key] == $arr_banner[$key - 1]){
                    for ($i = 1; $i <= $id_banner - 1; $i++) {
                        if($arr_banner[$key - $i] < 0){
                            $arr_banner[$key - $i] += 0;
                        }
                        else{
                            $arr_banner[$key - $i] -= 1;
                        }
                    }
                }

            }
        }

        // ДОБАВИТЬ MassiveForFlexSlider
        // ОБЯЗАТЕЛЬНО
        if(!$this -> arrayBridge($arr_banner , array_count_values($arr_banner))){
            return $arr_banner;
        }
        return $this -> arrayBridge($arr_banner , array_count_values($arr_banner));

    }



        /*
         * Здесь происходить соединкение массива и внесение новых ему значений.
         * Также этот метод осуществяет поиск одинаковых значений (т.е. возможных оодинаковых
         * значений number_position в таблице position. Есть вероятность того, что пользователь "задублировал"
         * некоторых номера позиций. В таком случае, будет верно предположить, что последнее значение
         * и есть актуальным, т.е. его и нужно в нести.
         * Этот метод работает (в совокупность с общим вызовом еще нескольких защиненных методов в методе
         * setNewNumber) как защита от дублирования.
        */
    public function arrayBridge(array $data_all , array $data_count_values){
        if(count($data_count_values) > 0){
            foreach($data_count_values as $key => $value){
                if($value == 1){
                    unset ($data_count_values[$key]);
                }
            }
            foreach ($data_all as $key => $value){
                if(array_key_exists( $value , $data_count_values)){
                    $data_all[$key] = $this -> randomPositionValue(max($data_all));
                }
            }
            return $data_all;
        }

        return false;
    }


    /*
     * Данный метод генерирует случайное число для внесения его в значение поля number_banner
     *в таблице banner. Этот метод также проверяет небыло-ли ранее сгенерированное
     * схожее значение, но для другого индекса. Этот метод вспомогательный и нужен в методе
     * arrayBridge, где происходит "перетасовка" значений поля  number_banner в таблице banner.
     */
    protected function randomPositionValue($max){

        $border =  10 ;
        $valueNew = rand($max , $max + $border);

        if(count($this -> lastRandomNumber) < 0){
            $this -> lastRandomNumber = $valueNew;
            return $valueNew;
        }
        if(count($this -> lastRandomNumber) > 0){
            $valueNew = rand($max , $max + $border);
            if(in_array($valueNew , $this -> lastRandomNumber)){
                $valueNew = rand($max , $max + $border);
                return $valueNew;
            }
            return $valueNew;
        }

        return $valueNew;

    }


 //=============================================================
 /*
  * Звершение части, отвечающей за логику работы с обработкой данных
  */
 //=============================================================


//==============================================================
/*
 * Здесь идут методы, которые отвечают за работу с иображениями,
 * их обработку и подготовку к сохранению.
 */


    /*
     * Метод, который определяет  какой процент  меньшее_значение составляет
     * от большего_значения. Возвращает число, которе и есть процент
     */
    protected function percent($greater_value ,$lower_value ){
        return ceil($lower_value * 100 / $greater_value);
    }

    /*
     * Метод, который определяет какой размер длины или ширины ( в зависимости от
     * того, что будет передано) будет составлять новое значение от значения ширины или высоты
     * переданных как стандарт в настрояках.
     */
    protected function valueForNewBorder ($percent , $border){
        return ceil($border * $percent / 100);
    }


    /*
     * Данный метод определяет новые нзачения ширины и высоты для картинок, ширина и высота
     * которых больше заданной в конфигурационныч настройках
     */
    public function getAllNewValueForBorder($width_img , $height_img){
        if($width_img <= $this -> width && $height_img <= $this -> height){
            $this -> new_width = $width_img;
            $this -> new_height = $height_img;
            return true;
        }

        if($width_img >= $this -> width && $height_img <= $this -> height){
            $this -> new_width = $this -> width;
            $this -> new_height = $this -> valueForNewBorder(
                $this -> percent($width_img , $height_img)
                , $this -> height
            );
            return true;
        }

        if($width_img <= $this -> width && $height_img >= $this -> height){
            $this -> new_height = $this -> height;
            $this -> new_width = $this -> valueForNewBorder(
                $this -> percent($height_img , $width_img)
                , $this -> width
            );
            return true;
        }

        if($width_img >= $this -> width && $height_img >= $this -> height){
            if($width_img > $height_img){
                $this -> new_width =  $this -> width;
                $this -> new_height = $this -> valueForNewBorder(
                    $this -> percent($width_img , $height_img)
                    , $this -> height
                );
                return true;
            }

            if($width_img < $height_img){
                $this -> new_height = $this -> height;
                $this -> new_width = $this -> valueForNewBorder(
                    $this -> percent($height_img , $width_img)
                    , $this -> width
                );
                return true;
            }
        }
        $this -> file_error['size'] = 'new value';

        return false;

    }

    /*
     * Метод позволяет определить какая именно встроенная функция PHP будет использована
     * для изменения размеров картинки
     */
    public function imgType($path){
        $this -> image_info = getimagesize($path);
        if($this -> image_info['mime'] == 'image/png'){
            $this -> image_create = imagecreatefrompng($path);
            return true;
        }
        if($this -> image_info['mime'] == 'image/jpeg' || $this -> image_info['mime'] == 'image/jpeg'){
            $this -> image_create = imagecreatefromjpeg($path);
            return true;
        }
        if($this -> image_info['mime'] == 'image/gif'){
            $this -> image_create = imagecreatefromgif($path);
            return true;
        }
        return false;
    }


    /*
     * Вся магия здесь.
     * Данный метод изменяет значения высоты и ширины новой картинки.
     * Важно помнить, что размеры картинки задаются в конфиг. файле.
     * исходя из этих заданных параметров происходит и вычитание
     * разметов ширины/высоты,которые будут заданы новому графическому
     *  файлу.
     */
    public function setNewSizeImg(array $file){

        $this -> filePath = $path = ROOT.$this -> path.$file['file']['name'];
        $this -> imgType($path);
        $this -> getAllNewValueForBorder($this -> image_info[0] , $this -> image_info[1]);
        $image_char = imagecreatetruecolor($this -> new_width , $this -> new_height);
        imagecopyresampled($image_char , $this -> image_create , 0 , 0 , 0 , 0 ,
            $this -> new_width , $this -> new_height ,
            imagesx($this -> image_create) , imagesy($this -> image_create)
        );
        imagejpeg($image_char , $path , 75);
    }


//=============================================================
/*
 * Завершение блока для работы с изображеиями
 */
//=============================================================


//=============================================================
/*
* Ниже подйдет та часть, которая есть вспомогательной - иметоды для получения
* инфо от БД
*/
//=============================================================

    /*
       * ДАнный метод вносит в БД и файловую директорию новую картинку
       * и данные по ней
       */

    public function insertSrcImg(array $data , $srcInDir){
        $sql = 'INSERT INTO ' . TABLE_BANNERS . ' VALUES(null , ? , ? , ? , ?)';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute([$data['name'] , $srcInDir ,
            $data['url'] ,  $data['status']]);
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
        $sql = 'UPDATE '. TABLE_BANNERS .' SET name="'. $data['name'] .'" ,  file_src="' . $srcInDir . '" ,
                    url="' . $data['url'] . '" ,  status="' . $data['status'] . '" WHERE id="' . $id . '"';
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


    public function deleteInBanners($id){
        $sql = 'DELETE FROM ' . TABLE_BANNERS . ' WHERE id="' . $id . '" ';
        return  $this -> connection -> query($sql);
    }

    public function deleteInPosition($id){

        $sql = 'DELETE FROM ' . TABLE_POSITION . ' WHERE id_banner="' . $id . '" ';
        return $this -> connection -> query($sql);

    }


}

$update = new Update();
$test = $update -> getAllPosition();