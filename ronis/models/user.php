<?php


class User extends Model{

    /*
     * Метод для получения все инфы о пользвателю по его логину
     */
    public function getByLogin($login){
        $sql = 'SELECT * FROM ' . TABLE_USERS . ' WHERE login="' . $login . '"';
        $query  = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);

        /*
         * Если запись существует, то вернуть элемент массива
         */
        if(isset($result[0])){
            return $result[0];
        }

        return false;
    }

    public function getTask(){
        $sql = 'SELECT * FROM ' . TABLE_TASK;
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}