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
        $query = "SELECT * FROM user";

        return $this->connection->fetchAllAssociative($query);
    }

    public function insertNewStorage(string $name) {
        $queryStorage = "INSERT INTO storages (name) VALUES (:name)";

        $paramsStorage = [
            'name' => $name,
        ];

        $this->connection->executeQuery($queryStorage, $paramsStorage);
        return $this->connection->lastInsertId();
    }

    public function updateUserStorage(array $userIds, int $lastStorageId) {
        $query = "UPDATE user SET storage_list_id = :lastStorageId 
             WHERE id IN (:employee)";

        $paramsUsers = [
            'employee' => $userIds,
            'lastStorageId' => $lastStorageId
        ];

        $this->connection->executeQuery($query, $paramsUsers, ['employee' => Connection::PARAM_INT_ARRAY]);
    }

}
