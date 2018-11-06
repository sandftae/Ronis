<?php


class Message extends Model
{
    /**
     * @var array
     */
    public $errors = [];

    /**
     * @param $data
     * @param null $id
     * @return bool
     */
    public function save($data, $id = null)
    {
        if (!isset($data['name']) || !isset($data['email']) || !isset($data['message'])) {
            return false;
        }

        $id = (int)$id;
        $name = $data['name'];
        $email = $data['email'];
        $message = $data['message'];

        if (!$id) {
            $this->insertMessage($name, $email, $message);
            return true;
        } else {
            $this->updateMessage($name, $email, $message, $id);
            return true;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validMessage(array $data)
    {
        if (trim($data['name']) == '') {
            $this->errors['name'] = 'Enter your name!';
            return false;
        }

        if (trim($data['email']) == '' || !is_string(filter_var($data['email'], FILTER_VALIDATE_EMAIL))) {
            $this->errors['email'] = 'Enter your correct email!';
            return false;
        }

        if (trim($data['message']) == '') {
            $this->errors['message'] = 'Enter message!';
            return false;
        }
        return true;
    }

    /**
     * @return array
     */
    public function getMessageErrors()
    {
        return $this->errors;
    }

    /**
     * @param $name
     * @param $email
     * @param $message
     * @return bool
     */
    public function insertMessage($name, $email, $message)
    {
        $sql = 'INSERT INTO ' . TABLE_MESSAGES . ' SET  name="' . $name . '" ,
                                                        email="' . $email . '" ,
                                                        messages="' . $message . '"';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * @param $name
     * @param $email
     * @param $message
     * @param $id
     * @return mixed
     */
    public function updateMessage($name, $email, $message, $id)
    {
        $sql = 'UPDATE ' . TABLE_MESSAGES . ' SET  name="' . $name . '" ,
                                                        email="' . $email . '" ,
                                                        messages="' . $message . '"
                                                        WHERE id="' . $id . '"';
        $conn = $this->database->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * @return bool|PDOStatement
     */
    public function getList()
    {
        $sql = 'SELECT * FROM ' . TABLE_MESSAGES . ' WHERE 1';
        return $this->connection->query($sql);
    }
}
