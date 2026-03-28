<?php
class User extends CoreModels
{
    private $table = 'users';
    public function __construct()
    {
        parent::__construct();
    }
    public function getAllUser()
    {
        return $this->getAll("SELECT * FROM {$this->table} ORDER BY created_at DESC");
    }

    public function getUserById($id)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getUserByPhone($phone)
    {
        return $this->getOne(
            "SELECT * FROM {$this->table} WHERE phone = :phone",
            ['phone' => $phone]
        );
    }
    public function getPassById($id)
    {
        return $this->getOne(
            "SELECT password FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }
    public function updateUser($data, $id)
    {
        return $this->update(
            $this->table,
            $data,
            'id',   // field
            $id     // value
        );
    }
    public function deleteUser($id)
    {
        return $this->delete(
            $this->table,
            'id',   // field
            $id     // value
        );
    }
    public function getUserByRole($role)
    {
        return $this->getAll("SELECT * FROM {$this->table} WHERE role = '$role'");
    }
    public function addUser($data)
    {
        return $this->insert($this->table, $data);
    }
}
