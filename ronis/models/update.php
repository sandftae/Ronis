<?php

class Update extends Model
{

    /**
     * @var int
     */
    protected $max_file_size = MAX_LOADING_FILE_SIZE;

    /**
     * @var $sql
     */
    public $sql;

    /**
     * @var $masImgSrc
     */
    public $masImgSrc;

    /**
     * @var $full_dir_img
     */
    public $full_dir_img;

    /**
     * @var array
     */
    protected $lastRandomNumber = [];

    /**
     * @var array
     */
    protected $upload_errors = [
        UPLOAD_ERR_OK => "No errros",
        UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize",
        UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE",
        UPLOAD_ERR_PARTIAL => "Partial upload",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory",
        UPLOAD_ERR_CANT_WRITE => "Can`t write to disk",
        UPLOAD_ERR_EXTENSION => "File upload stopped by extension",
        UPLOAD_ERR_NO_FILE => "No file"
    ];

    /**
     * @var array
     */
    public $file_error = [];

    /**
     * @var int
     */
    protected $width = MAX_WIDTH;

    /**
     * @var int
     */
    protected $height = MAX_HEIGHT;

    /**
     * @var $filePath
     */
    public $filePath;

    /**
     * @var $image_info
     */
    public $image_info;

    /**
     * @var $image_create
     */
    public $image_create;

    /**
     * @var $new_width
     */
    public $new_width;

    /**
     * @var $new_height
     */
    public $new_height;

    /**
     * @var string
     */
    public $path = PATH_FOR_IMG;

    /**
     * @var array
     */
    protected $endMass = [];

    /**
     * @param array $file
     * @param array $data
     * @return bool
     */
    public function insertInDir(array $file, array $data)
    {
        if (is_uploaded_file($_FILES['file']['tmp_name'])) {
            move_uploaded_file($file['file']['tmp_name'], ROOT . $this->path . $file['file']['name']);
            $this->insertSrcImg($data, PATH_TO_IMG_FOR_SLIDER . $file['file']['name']);
            return true;

        } else {
            $this->file_error[] = 'have error in validation! hack?';
            unset($file);
            return false;
        }
    }

    /**
     * @param array $file
     * @return bool
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
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_INI_SIZE];
                    break;
                case 2:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_FORM_SIZE];
                    break;
                case 3:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_PARTIAL];
                    break;
                case 4:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_NO_TMP_DIR];
                    break;
                case 6:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_CANT_WRITE];
                    break;
                case 7:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_EXTENSION];
                    break;
                case 8:
                    $this->file_error[] = $this->upload_errors[UPLOAD_ERR_NO_FILE];
                    break;
            }
            return false;
        }

        if ($file['file']['type'] != 'image/gif'
            && $file['file']['type'] != 'image/png'
            && $file['file']['type'] != 'image/jpg'
            && $file['file']['type'] != 'image/jpeg'
        ) {
            $this->file_error[] = 'invalid image format';
            return false;
        }

        if ($file['file']['size'] > $this->max_file_size) {
            $this->file_error[] = 'the maximum size is 5 mb';
            return false;
        }
        return true;
    }

    public function validField($data)
    {
        if (empty($data['name'])) {
            $this->file_error['error_fields']['name_error'] = 'field is empty';
        }

        if (empty($data['url'])) {
            $this->file_error['error_fields']['url_error'] = 'field is empty';
        }

        if (empty($data['status'])) {
            $this->file_error['error_fields']['status_error'] = 'field is empty';
        }
        if (empty($data['position'])) {
            $this->file_error['error_fields']['position_error'] = 'field is empty';
        }

        if ($data['position'] < 0 && !is_numeric($data['position'])) {
            $this->file_error['error_fields']['position_error']
                = 'the value of the "position" field is less than zero or not the number';
        }

        if (count($this->file_error['error_fields']) > 0) {
            return false;
        }
        return true;
    }

    public function getImgArr()
    {
        $this->masImgSrc = $this->getAllSrc();
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->file_error;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->full_dir_img;
    }

    /**
     * @param $id_banner
     * @param $new_number
     * @return array|bool
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

                if ($arr_banner[$key] == $arr_banner[$key + 1]) {
                    for ($i = 1; $i <= (count($arr_banner) - $key); $i++) {
                        $arr_banner[$key + $i] += 1;
                    }
                }
                if ($arr_banner[$key] == $arr_banner[$key - 1]) {
                    for ($i = 1; $i <= $id_banner - 1; $i++) {
                        if ($arr_banner[$key - $i] < 0) {
                            $arr_banner[$key - $i] += 0;
                        } else {
                            $arr_banner[$key - $i] -= 1;
                        }
                    }
                }
            }
        }

        if (!$this->arrayBridge($arr_banner, array_count_values($arr_banner))) {
            return $arr_banner;
        }
        return $this->arrayBridge($arr_banner, array_count_values($arr_banner));
    }

    /**
     * @param array $data_all
     * @param array $data_count_values
     * @return array|bool
     */
    public function arrayBridge(array $data_all, array $data_count_values)
    {
        if (count($data_count_values) > 0) {
            foreach ($data_count_values as $key => $value) {
                if ($value == 1) {
                    unset($data_count_values[$key]);
                }
            }
            foreach ($data_all as $key => $value) {
                if (array_key_exists($value, $data_count_values)) {
                    $data_all[$key] = $this->randomPositionValue(max($data_all));
                }
            }
            return $data_all;
        }
        return false;
    }


    /**
     * @param $max
     * @return int
     */
    protected function randomPositionValue($max)
    {
        $border = 10;
        $valueNew = rand($max, $max + $border);

        if (count($this->lastRandomNumber) < 0) {
            $this->lastRandomNumber = $valueNew;
            return $valueNew;
        }
        if (count($this->lastRandomNumber) > 0) {
            $valueNew = rand($max, $max + $border);
            if (in_array($valueNew, $this->lastRandomNumber)) {
                $valueNew = rand($max, $max + $border);
                return $valueNew;
            }
            return $valueNew;
        }
        return $valueNew;
    }

    /**
     * @param $greater_value
     * @param $lower_value
     * @return float
     */
    protected function percent($greater_value, $lower_value)
    {
        return ceil($lower_value * 100 / $greater_value);
    }

    /**
     * @param $percent
     * @param $border
     * @return float
     */
    protected function valueForNewBorder($percent, $border)
    {
        return ceil($border * $percent / 100);
    }

    /**
     * @param $width_img
     * @param $height_img
     * @return bool
     */
    public function getAllNewValueForBorder($width_img, $height_img)
    {
        if ($width_img <= $this->width && $height_img <= $this->height) {
            $this->new_width = $width_img;
            $this->new_height = $height_img;
            return true;
        }

        if ($width_img >= $this->width && $height_img <= $this->height) {
            $this->new_width = $this->width;
            $this->new_height = $this->valueForNewBorder(
                $this->percent($width_img, $height_img),
                $this->height
            );
            return true;
        }

        if ($width_img <= $this->width && $height_img >= $this->height) {
            $this->new_height = $this->height;
            $this->new_width = $this->valueForNewBorder(
                $this->percent($height_img, $width_img),
                $this->width
            );
            return true;
        }

        if ($width_img >= $this->width && $height_img >= $this->height) {
            if ($width_img > $height_img) {
                $this->new_width = $this->width;
                $this->new_height = $this->valueForNewBorder(
                    $this->percent($width_img, $height_img),
                    $this->height
                );
                return true;
            }

            if ($width_img < $height_img) {
                $this->new_height = $this->height;
                $this->new_width = $this->valueForNewBorder(
                    $this->percent($height_img, $width_img),
                    $this->width
                );
                return true;
            }
        }
        $this->file_error['size'] = 'new value';

        return false;
    }

    /**
     * @param $path
     * @return bool
     */
    public function imgType($path)
    {
        $this->image_info = getimagesize($path);
        if ($this->image_info['mime'] == 'image/png') {
            $this->image_create = imagecreatefrompng($path);
            return true;
        }
        if ($this->image_info['mime'] == 'image/jpeg' || $this->image_info['mime'] == 'image/jpeg') {
            $this->image_create = imagecreatefromjpeg($path);
            return true;
        }
        if ($this->image_info['mime'] == 'image/gif') {
            $this->image_create = imagecreatefromgif($path);
            return true;
        }
        return false;
    }


    /**
     * @param array $file
     */
    public function setNewSizeImg(array $file)
    {
        $this->filePath = $path = ROOT . $this->path . $file['file']['name'];
        $this->imgType($path);
        $this->getAllNewValueForBorder($this->image_info[0], $this->image_info[1]);
        $image_char = imagecreatetruecolor($this->new_width, $this->new_height);
        imagecopyresampled($image_char, $this->image_create, 0, 0, 0, 0,
            $this->new_width, $this->new_height,
            imagesx($this->image_create), imagesy($this->image_create)
        );
        imagejpeg($image_char, $path, 75);
    }

    /**
     * @param array $data
     * @param $srcInDir
     * @return bool
     */
    public function insertSrcImg(array $data, $srcInDir)
    {
        $sql = 'INSERT INTO ' . TABLE_BANNERS . ' VALUES(null , ? , ? , ? , ?)';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute([$data['name'], $srcInDir,
            $data['url'], $data['status']]);
        return $result;
    }

    /**
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
     * @param $id
     * @param array $data
     * @param $srcInDir
     * @return bool
     */
    public function updateImgInfo($id, array $data, $srcInDir)
    {
        $sql = 'UPDATE ' . TABLE_BANNERS . ' SET name="' . $data['name'] . '" ,  file_src="' . $srcInDir . '" ,
                    url="' . $data['url'] . '" ,  status="' . $data['status'] . '" WHERE id="' . $id . '"';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
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

    /**
     * @param $id
     * @return bool|PDOStatement
     */
    public function deleteInBanners($id)
    {
        $sql = 'DELETE FROM ' . TABLE_BANNERS . ' WHERE id="' . $id . '" ';
        return $this->connection->query($sql);
    }

    /**
     * @param $id
     * @return bool|PDOStatement
     */
    public function deleteInPosition($id)
    {
        $sql = 'DELETE FROM ' . TABLE_POSITION . ' WHERE id_banner="' . $id . '" ';
        return $this->connection->query($sql);
    }
}