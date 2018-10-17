<?php

class Page extends Model{

  

    public function save($data , $id = null){
        if(!isset($data['alias']) || !isset($data['title']) || !isset($data['content'])){
            return false;
        }

        $id = (int) $id;
        $alias = $data['alias'];
        $title = $data['title'];
        $content = $data['content'];
        $is_published = isset($data['is_published']) ? 1 : 0;

        if(!$id){
            $this -> insertContent($alias , $title , $content);
            return true;
        }else{
            $this -> updateContent($alias , $title , $content , $is_published , $id);
            return true;
       }
    }

    public function delete($id){
        $id = (int) $id;
        $sql = 'DELETE FROM ' . TABLE_PAGES . ' WHERE id="' . $id . '" ';
        return  $this -> connection -> query($sql);
    }

    /*
     * Данный метод позволяет добавлять новую запись
     */
    public function insertContent($alias , $title , $content){
        $sql = 'INSERT INTO ' . TABLE_PAGES . ' SET  alias="'.$alias.'" ,
                                                        title="'.$title.'" ,
                                                        content="'.$content.'"';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }

    /*
     * Здесь происходит обновление
     */
    public function updateContent($alias , $title , $content , $is_published , $id){
        $sql = 'UPDATE ' . TABLE_PAGES . ' SET  alias="'.$alias.'" ,
                                                title="'.$title.'" ,
                                                content="'.$content.'" ,
                                                is_published="'.$is_published.'"
                                                 WHERE id="'.$id.'" ';
        $conn = $this -> connection -> prepare($sql);
        $result = $conn -> execute();
        return $result;
    }

    public function getList($only_published = false){
        $sql = 'SELECT * FROM '. TABLE_PAGES .' WHERE 1';

        if($only_published){
            $sql .= ' AND is_published = 1';
        }

        return $this -> connection -> query($sql);
    }

    public function getByAlias($alias){
        $sql = 'SELECT * FROM ' . TABLE_PAGES . ' WHERE alias="' . $alias . '" LIMIT 1';
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return isset($result[0]) ? $result[0] : null;
    }

    public function getById($id){
        $id = (int)$id;
        $sql = 'SELECT * FROM ' . TABLE_PAGES . ' WHERE id="' . $id . '" LIMIT 1';
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return isset($result[0]) ? $result[0] : null;
    }

    /*
     * Данный метод позволяет получить все данные из таблицы banners.
     * Это позволит при отпработке котнроллера запстить метод ,
     * которые покажет в какоам порядке выводить инфо - MassiveForFlexSlider
     */
    public function getAllSrc(){
        $sql = 'SELECT * FROM ' . TABLE_BANNERS ;
        $query = $this -> connection -> query($sql);
        $result = $query -> fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

}


