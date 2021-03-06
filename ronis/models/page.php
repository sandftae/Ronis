<?php

class Page extends Model
{
    /**
     * @param $data
     * @param null $id
     * @return bool
     */
    public function save($data, $id = null)
    {
        if (!isset($data['alias']) || !isset($data['title']) || !isset($data['content'])) {
            return false;
        }

        $id = (int)$id;
        $alias = $data['alias'];
        $title = $data['title'];
        $content = $data['content'];
        $is_published = isset($data['is_published']) ? 1 : 0;

        if (!$id) {
            $this->insertContent($alias, $title, $content);
            return true;
        } else {
            $this->updateContent($alias, $title, $content, $is_published, $id);
            return true;
        }
    }

    /**
     * @param $id
     * @return bool|PDOStatement
     */
    public function delete($id)
    {
        $id = (int)$id;
        $sql = 'DELETE FROM ' . TABLE_PAGES . ' WHERE id="' . $id . '" ';
        return $this->connection->query($sql);
    }

    /**
     * @param $alias
     * @param $title
     * @param $content
     * @return bool
     */
    public function insertContent($alias, $title, $content)
    {
        $sql = 'INSERT INTO ' . TABLE_PAGES . ' SET  alias="' . $alias . '" ,
                                                        title="' . $title . '" ,
                                                        content="' . $content . '"';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * @param $alias
     * @param $title
     * @param $content
     * @param $is_published
     * @param $id
     * @return bool
     */
    public function updateContent($alias, $title, $content, $is_published, $id)
    {
        $sql = 'UPDATE ' . TABLE_PAGES . ' SET  alias="' . $alias . '" ,
                                                title="' . $title . '" ,
                                                content="' . $content . '" ,
                                                is_published="' . $is_published . '"
                                                 WHERE id="' . $id . '" ';
        $conn = $this->connection->prepare($sql);
        $result = $conn->execute();
        return $result;
    }

    /**
     * @param bool $only_published
     * @return bool|PDOStatement
     */
    public function getList($only_published = false)
    {
        $sql = 'SELECT * FROM ' . TABLE_PAGES . ' WHERE 1';

        if ($only_published) {
            $sql .= ' AND is_published = 1';
        }

        return $this->connection->query($sql);
    }

    /**
     * @param $alias
     * @return null
     */
    public function getByAlias($alias)
    {
        $sql = 'SELECT * FROM ' . TABLE_PAGES . ' WHERE alias="' . $alias . '" LIMIT 1';
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return isset($result[0]) ? $result[0] : null;
    }

    /**
     * @param $id
     * @return null
     */
    public function getById($id)
    {
        $id = (int)$id;
        $sql = 'SELECT * FROM ' . TABLE_PAGES . ' WHERE id="' . $id . '" LIMIT 1';
        $query = $this->connection->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return isset($result[0]) ? $result[0] : null;
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
}
