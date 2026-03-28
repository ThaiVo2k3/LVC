<?php
class CoreModels
{
    protected $conn;

    public function __construct()
    {
        $this->conn = Database::connectPDO();
    }

    /* ================== SELECT ================== */

    public function getAll($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOne($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRow($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }

    public function fetchColumn($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    /* ================== INSERT ================== */

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $columns = implode(',', $keys);
        $placeholders = ':' . implode(',:', $keys);

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute($data);
    }

    /* ================== UPDATE ================== */

    public function update($table, $data, $whereField, $whereValue)
    {
        $set = [];
        $params = [];

        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
            $params[$key] = $value;
        }

        $params['where_value'] = $whereValue;

        $setString = implode(', ', $set);

        $sql = "UPDATE $table SET $setString WHERE $whereField = :where_value";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($params);
    }

    /* ================== DELETE ================== */

    public function delete($table, $whereField, $whereValue)
    {
        $sql = "DELETE FROM $table WHERE $whereField = :where_value";
        $stmt = $this->conn->prepare($sql);

        return $stmt->execute(['where_value' => $whereValue]);
    }

    public function lastID()
    {
        return $this->conn->lastInsertId();
    }
}
