<?php

/*
 * Данный класс отвечает за валидацию отправляемых
 * сообщений пользователя (те, которые не админы:)),
 * а также сохранение в БД их и вывод во вью для админов
 */
class Message extends Model{
    public $errors = [];

    /*
     * Данный метод либо добавляет, либо обновляет таблицу  MESSAGE`s
     */
    public function save($data , $id = null){
        if(!isset($data['name']) || !isset($data['email']) || !isset($data['message'])){
            return false;
        }

        $id = (int) $id;
        $name = $data['name'];
        $email = $data['email'];
        $message = $data['message'];

        if(!$id){
            $this -> insertMessage($name , $email , $message);
            return true;
        }else{
            $this -> updateMessage($name , $email , $message , $id);
            return true;
        }

    }

    public function validMessage(array $data){
        if(trim($data['name']) == ''){
            $this -> errors['name'] = 'Enter your name!';
            return false;
        }

        if(trim($data['email']) == '' || !is_string(filter_var($data['email'] , FILTER_VALIDATE_EMAIL))){
            $this -> errors['email'] = 'Enter your correct email!';
            return false;
        }

        if(trim($data['message']) == ''){
            $this -> errors['message'] = 'Enter message!';
            return false;
        }
        return true;
    }

    public function getMessageErrors(){
        return $this -> errors;
    }

/*
 * Данный метод позволяет добавлять новую запись
 */
    public function insertMessage($name , $email , $message){
        $sql = 'INSERT INTO ' . TABLE_MESSAGES . ' SET  name="'.$name.'" ,
                                                        email="'.$email.'" ,
                                                        messages="'.$message.'"';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }

    /*
     * Здесь происходит обновление
     */
    public function updateMessage($name , $email , $message , $id){
        $sql = 'UPDATE ' . TABLE_MESSAGES . ' SET  name="'.$name.'" ,
                                                        email="'.$email.'" ,
                                                        messages="'.$message.'"
                                                        WHERE id="'.$id.'"';
        $conn = $this -> database -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }


        /*
         * ДАнный метод отображает все сообщения
         */
        public function getList(){
            $sql = 'SELECT * FROM '. TABLE_MESSAGES .' WHERE 1';
            return $this -> connection -> query($sql);
        }
}
