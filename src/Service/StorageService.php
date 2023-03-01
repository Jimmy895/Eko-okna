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
}
