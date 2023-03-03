<?php

namespace App\Repository;

use Doctrine\DBAL\Connection;

class StorageRepository
{
    private Connection $connection;

    public function __construct(Connection $connection) {
        $this->connection = $connection;
    }


    public function selectAllUsers() {
        $sql = "SELECT * FROM user";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function insertNewStorage(string $name) {
        $sqlStorage = "INSERT INTO storages (name) VALUES (:name)";

        $param = [
            'name' => $name,
        ];

        $this->connection->executeQuery($sqlStorage, $param);
        return $this->connection->lastInsertId();
    }

    public function updateUserStorage(array $userIds, int $lastStorageId) {
        $sql = "UPDATE user SET storage_list_id = :lastStorageId 
             WHERE id IN (:employee)";

        $param = [
            'employee' => $userIds,
            'lastStorageId' => $lastStorageId
        ];

        $this->connection->executeQuery($sql, $param, ['employee' => Connection::PARAM_INT_ARRAY]);
    }

    public function selectAllStorages() {
        $sql = "SELECT * FROM storages";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function selectAllUnits() {
        $sql = "SELECT * FROM units";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function insertNewArticle(string $name, int $unit_id) {
        $sql = "INSERT INTO articles_list (name, unit_id) VALUES (:name, :unit_id)";

        $param = [
            'name' => $name,
            'unit_id' => $unit_id,
        ];

        $this->connection->executeQuery($sql, $param);
    }

    public function selectStorages() {
        $sql = "SELECT * FROM storages";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function selectEmplyeesFromStorage(int $id) {
        $sql = "SELECT login FROM user WHERE storage_list_id = :id";

        $param['id'] = $id;

        return $this->connection->fetchAllAssociative($sql, $param);
    }

    public function selectArticlesInStorages(int $id) {
        $sql = "SELECT id FROM articles WHERE storages_list_id = :id";

        $param['id'] = $id;

        return $this->connection->fetchAllAssociative($sql, $param);
    }

    public function selectStorage(int $id)
    {
        $sql = "SELECT * FROM storages WHERE id = :id";
        $param['id'] = $id;

        return $this->connection->fetchAssociative($sql, $param);
    }

    public function updateStorageName(int $id, string $name) {
        $sql = "UPDATE storages
        SET name = :name
        WHERE id = :id";
        $param = [
            'name' => $name,
            'id' => $id
        ];

        $this->connection->executeQuery($sql, $param);
    }

    public function selectUsersDataForList() {
        $sql = "SELECT u.login, u.roles, s.name
        FROM user u
        INNER JOIN storages s ON u.storage_list_id = s.id";

        return $this->connection->fetchAllAssociative($sql);
    }


}

