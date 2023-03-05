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

    public function selectStorages()
    {
        $sql = "SELECT * FROM storages";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function selectEmplyeesFromStorage(int $id)
    {
        $sql = "SELECT login FROM user WHERE storage_list_id = :id";

        $param['id'] = $id;

        return $this->connection->fetchAllAssociative($sql, $param);
    }

    public function selectArticlesInStorages(int $id)
    {
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

    public function updateStorageName(int $id, string $name)
    {
        $sql = "UPDATE storages
        SET name = :name
        WHERE id = :id";
        $param = [
            'name' => $name,
            'id' => $id
        ];

        $this->connection->executeQuery($sql, $param);
    }

    public function selectUsersDataForList()
    {
        $sql = "SELECT u.id, u.login, u.roles, s.name
        FROM user u
        INNER JOIN storages s ON u.storage_list_id = s.id";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function selectUser(int $id)
    {
        $sql = "SELECT u.id, u.login, u.roles, u.storage_list_id FROM user u WHERE id = :id";
        $param['id'] = $id;

        return $this->connection->fetchAssociative($sql, $param);
    }

    public function updateUserEditStorage(string $login, int $storage_id)
    {
        $sql = "UPDATE user
        SET storage_list_id = :storage_id
        WHERE login = :login";

        $param = [
            'login' => $login,
            'storage_id' => $storage_id
        ];

        return $this->connection->executeQuery($sql, $param);
    }

    public function selectUnitForArticle(int $articleId)
    {
        $sql = "SELECT u.id FROM units u JOIN articles_list a on u.id = a.unit_id WHERE a.id = :articleId";

        $param['articleId'] = $articleId;

        return $this->connection->fetchAssociative($sql, $param);
    }

    public function selectAllArticlesList()
    {
        $sql = "SELECT al.id, al.name, u.unit 
        FROM articles_list al
        JOIN units u ON al.unit_id = u.id";

        return $this->connection->fetchAllAssociative($sql);
    }

    public function selectArticleToEdit(int $id)
    {
        $sql = "SELECT * FROM articles_list WHERE id = :id";
        $param['id'] = $id;

        return $this->connection->fetchAssociative($sql, $param);
    }

    public function updateArticleName(int $id, string $name)
    {
        $sql = "UPDATE articles_list
        SET name = :name
        WHERE id = :id";

        $param = [
            'id' => $id,
            'name' => $name,
        ];

        return $this->connection->executeQuery($sql, $param);
    }

    public function checkIfArticleExists(int $id, ?int $code)
    {
        $sql = "SELECT id, name_id, amount,code FROM articles WHERE name_id = $id AND code = $code";

        return $this->connection->fetchAssociative($sql);
    }

    public function entryUpdateArticle(int $id, float $amount, float $vat, float $price, ?string $filePath, ?int $code)
    {
        $sql = "UPDATE articles 
                SET amount = :amount, vat = :vat, price = :price, file_path = :filePath 
                WHERE id = $id AND code = $code";

        $param = [
            'id' => $id,
            'amount' => $amount,
            'vat' => $vat,
            'price' => $price,
            'filePath' => $filePath,
            'code' => $code,
        ];

        return $this->connection->executeQuery($sql, $param);
    }

    public function entryArticle(int $id, float $amount, float $vat, float $price, int $unitName, int $code, int $storageId, ?string $filePath = null)
    {
        $sql = "INSERT INTO articles (name_id, amount, unit_id, vat, price, storages_list_id, file_path, code) VALUES (:name_id, :amount, :unit_id, :vat, :price, :storages_list_id, :file_path, :code)";

        $param = [
            'name_id' => $id,
            'amount' => $amount,
            'unit_id' => $unitName,
            'vat' => $vat,
            'price' => $price,
            'storages_list_id' => $storageId,
            'file_path' => $filePath,
            'code' => $code,
        ];

        return $this->connection->executeQuery($sql, $param);
    }

    public function selectAllArticlesListWithAmount(?int $storageId = null)
    {
        $param = [];
        $sql = "SELECT a.id, a.amount, a.code, al.name, u.unit, s.name as storage_name
        FROM articles a
        JOIN articles_list al ON a.name_id = al.id
        JOIN units u ON a.unit_id = u.id
        JOIN storages s ON s.id = a.storages_list_id 
        WHERE a.amount > 0
        ";

        if ($storageId) {
            $sql .= " AND a.storages_list_id = :storageId";
            $param['storageId'] = $storageId;
        }

        return $this->connection->fetchAllAssociative($sql, $param);
    }

    public function checkAmountToRelease(int $id)
    {
        $sql = "SELECT amount FROM articles WHERE id = $id";

        return $this->connection->fetchAssociative($sql);
    }

    public function releaseArticle(int $id, ?float $amount, int $code)
    {
        $sql = "UPDATE articles SET amount = :amount WHERE id = :id AND code = :code";

        $param = [
            'id' => $id,
            'amount' => $amount,
            'code' => $code,
        ];

        return $this->connection->executeQuery($sql, $param);
    }

}

