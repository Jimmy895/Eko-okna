<?php

namespace App\Service;

use App\Repository\StorageRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class StorageService
{
    private StorageRepository $storageRepo;

    public function __construct(StorageRepository $storageRepo) {
        $this->storageRepo = $storageRepo;
    }

    public function prepareUsersArrayForSelect() {
        $users = $this->storageRepo->selectAllUsers();
        $usersForSelect = [];

        foreach ($users as $user) {
            $usersForSelect[$user['login']] = $user['id'];
        }

        return $usersForSelect;
    }

    public function insertNewStorage(array $data) {
        $lastStorageId = $this->storageRepo->insertNewStorage($data['name']);
        $this->storageRepo->updateUserStorage($data['employee'], $lastStorageId);
    }

    public function prepareStorageArrayForSelect() {
        $storages = $this->storageRepo->selectAllStorages();
        $storagesForSelect = [];

        foreach ($storages as $storage) {
            $storagesForSelect[$storage['name']] = $storage['id'];
        }

        return $storagesForSelect;
    }

    public function prepareUnitsArrayForSelect() {
        $units = $this->storageRepo->selectAllUnits();
        $unitsForSelect = [];

        foreach ($units as $unit) {
            $unitsForSelect[$unit['unit']] = $unit['id'];
        }

        return $unitsForSelect;
    }

    public function insertNewArticle(array $data) {
        $this->storageRepo->insertNewArticle($data['name'], $data['unit']);
    }

    public function selectStorages() {
        return $this->storageRepo->selectStorages();
    }

    public function selectEmplyeesFromStorages() {
        $storages = $this->storageRepo->selectStorages();

        foreach ($storages as $storageKey => &$storage)  {
            $storages[$storageKey]['employees'] = $this->storageRepo->selectEmplyeesFromStorage($storage['id']) ?? null;
        }

        $articles = $this->storageRepo->selectStorages();

        foreach ($articles as $articleKey => &$article)  {
            $storages[$articleKey]['articles'] = $this->storageRepo->selectArticlesInStorages($article['id']) ?? null;
        }


        return $storages;
    }

    public function selectStorage(int $id)
    {
        return $this->storageRepo->selectStorage($id);
    }

    public function updateStorageName(int $id, string $name)
    {
        $this->storageRepo->updateStorageName($id, $name);
    }

    public function selectAllUsers()
    {
        return $this->storageRepo->selectUsersDataForList();
    }

    public function selectUser(int $id)
    {
        return $this->storageRepo->selectUser($id);
    }

//    public function selectStoragesForUserEdit()
//    {
//        return $this->storageRepo->selectStoragesForUserEdit();
//    }

    public function updateUserStorage(string $login, int $storage_id)
    {
        return $this->storageRepo->updateUserEditStorage($login, $storage_id);
    }

    public function prepareArticlesList(): array
    {
        $articleList = $this->storageRepo->selectAllArticlesList();
        $articleListForSelect = [];

        foreach ($articleList as $article) {
            $articleListForSelect["{$article['name']} ({$article['unit']})"] = $article['id'] ;
        }

        return $articleListForSelect;
    }

    public function prepareArticlesForList() {
        return $this->storageRepo->selectAllArticlesList();
    }

    public function selectArticleToEdit(int $id)
    {
        return $this->storageRepo->selectArticleToEdit($id);
    }

    public function updateArticleName(int $id, string $name)
    {
        return $this->storageRepo->updateArticleName($id, $name);
    }

    public function entryArticle(array $data, ?string $filePath) {
        $unitId = $this->storageRepo->selectUnitForArticle($data['article']);
        $checkIfAlreadyExists = $this->storageRepo->checkIfArticleExists($data ['article'], $data['code']);
        if ($checkIfAlreadyExists) {
            $amountToSet = $checkIfAlreadyExists['amount'] + $data['amount'];

            return $this->storageRepo->entryUpdateArticle($checkIfAlreadyExists['id'], $amountToSet, $data['vat'], $data['price'], $filePath, $data['code']);
        } else {
            return $this->storageRepo->entryArticle($data['article'], $data['amount'], $data['vat'], $data['price'], $unitId['id'], $data['code'], $data['storage_list_id'], $filePath);
        }
    }

    public function prepareArticlesListForRelease(?int $storageId = null) {
        $articleList = $this->storageRepo->selectAllArticlesListWithAmount($storageId);
        $articleListForSelect = [];
        foreach ($articleList as $article) {
            $articleListForSelect[$article['storage_name']]["{$article['name']} - dostępne: {$article['amount']} ({$article['unit']}) kod: {$article['code']}"] = $article['id'] ;
        }

        return $articleListForSelect;
    }

    public function releaseArticle(array $data) {
        $getCurrentAmount = $this->storageRepo->checkAmountToRelease($data['article']);

        if($getCurrentAmount['amount'] < $data['amount']) {

            return false;
        }
        else {
            $amountToSet = $getCurrentAmount['amount'] - $data['amount'];
            $this->storageRepo->releaseArticle($data['article'], $amountToSet, $data['code']);

            return true;
        }
    }
}
