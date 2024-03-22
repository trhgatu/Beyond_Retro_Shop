<?php


class User {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function addUser($data) {
        return insert('user', $data);
    }

    public function updateUser($data, $condition = '') {
        return update('user', $data, $condition);
    }

    public function deleteUser($condition = '') {
        return delete('user', $condition);
    }

    public function getUser($condition = '') {
        $sql = "SELECT * FROM user";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        return getRaw($sql);
    }

    public function getOneUser($condition = '') {
        $sql = "SELECT * FROM user";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        return oneRaw($sql);
    }

    public function countUsers($condition = '') {
        $sql = "SELECT COUNT(*) FROM user";
        if (!empty($condition)) {
            $sql .= " WHERE $condition";
        }
        return getRows($sql);
    }
}
