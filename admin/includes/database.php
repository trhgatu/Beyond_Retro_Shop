<!-- Các hàm xử lý liên quan đến database -->
<?php
if (!defined("_CODE")) {
    die("Access Denied !");
}
function query($sql, $data = [], $check = false)
{
    global $conn;
    $ketqua = false;
    try {
        $statement = $conn->prepare($sql);
        if (!empty($data)) {
            $ketqua = $statement->execute($data);
        } else {
            $ketqua = $statement->execute();
        }
    } catch (Exception $exception) {
        echo $exception->getMessage();
        echo 'File' . $exception->getFile();
        echo 'Line' . $exception->getLine();
    }
    if ($check) {
        return $statement;
    }
    return $ketqua;
}
//Hàm insert
function insert($table, $data)
{
    $key = array_keys($data);
    $truong = implode(',', $key);
    $valuetb = ':' . implode(',:', $key);

    $sql = 'INSERT INTO ' . $table . '(' . $truong . ')' . 'VALUES(' . $valuetb . ')';
    $kq = query($sql, $data);
    return $kq;
}
//Hàm update
function update($table, $data, $condition = '')
{
    global $conn;
    $update = '';
    foreach ($data as $key => $value) {
        $update .= $key . '= :' . $key . ', ';
    }
    $update = rtrim($update, ', ');

    $sql = 'UPDATE ' . $table . ' SET ' . $update;
    if (!empty($condition)) {
        $sql .= ' WHERE ' . $condition;
    }

    try {
        $statement = $conn->prepare($sql);
        foreach ($data as $key => &$value) {
            $statement->bindParam(':' . $key, $value);
        }
        $ketqua = $statement->execute();
        return $ketqua; // Trả về true nếu cập nhật thành công, false nếu không thành công
    } catch (PDOException $e) {
        return false; // Cập nhật không thành công
    }
}
//Hàm delete
function delete($table, $condition = '')
{
    if (empty($condition)) {
        $sql = 'DELETE FROM ' . $table;

    } else {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $condition;
    }
    $kq = query($sql);
    return $kq;
}
//Lấy nhiều dòng dữ liệu
function getRaw($sql)
{
    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq->fetchAll(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Lấy 1 dòng dữ liệu
function oneRaw($sql)
{
    $kq = query($sql, '', true);
    if (is_object($kq)) {
        $dataFetch = $kq->fetch(PDO::FETCH_ASSOC);
    }
    return $dataFetch;
}
//Đếm số dòng dữ liệu
function getRows($sql)
{
    $kq = query($sql, '', true);
    if (!empty($kq)) {
        return $kq->rowCount();
    }
}