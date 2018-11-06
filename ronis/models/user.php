<?php

class User extends Model
{
    /**
     * @param $login
     * @return bool
     */
    public function getByLogin($login)
    {
        $sql = 'SELECT * FROM ' . TABLE_USERS . ' WHERE login="' . $login . '"';
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        if (isset($result[0])) {
            return $result[0];
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getTask()
    {
        $sql = 'SELECT * FROM ' . TABLE_TASK;
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
    }
}
