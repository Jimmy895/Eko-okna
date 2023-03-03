<?php

namespace App\Service;

use App\Repository\StorageRepository;

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
}
