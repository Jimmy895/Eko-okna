<?php

namespace App\Controller;

use App\Form\CreateArticleType;
use App\Form\CreateNewStorageType;
use App\Form\EditUserType;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminController extends AbstractController
{
    private StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    #[Route('/storages/create', name: 'create_storage')]
    public function createStorage(Request $request): Response
    {
        $form = $this->createForm(CreateNewStorageType::class, null, ['employee' => $this->storageService->prepareUsersArrayForSelect(), 'create' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Utworzono magazyn!');
            $data = $form->getData();
            $this->storageService->insertNewStorage($data);
        }

        return $this->render('admin/storages/create_storage.html.twig', [
            'form' => $form->createView(),
            'title' => 'Stwórz magazyn'
        ]);
    }

    #[Route('/storages', name: 'storage_list')]
    public function storages(): Response
    {
        return $this->render('admin/storages/storages_list.html.twig', [
            'controller_name' => 'AdminController',
            'storages' => $this->storageService->selectEmplyeesFromStorages()
        ]);
    }

    #[Route('/storages/edit/{id}', name: 'edit_storage')]
    public function editStorage(int $id, Request $request): Response
    {
        $storage = $this->storageService->selectStorage($id);
        $form = $this->createForm(CreateNewStorageType::class, ['name' => $storage['name']]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->storageService->updateStorageName($id, $data['name']);

            return $this->redirectToRoute('storage_list');
        }

        return $this->render('admin/storages/create_storage.html.twig', [
            'controller_name' => 'AdminController',
            'storage' => $storage,
            'title' => 'Edytuj magazyn',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/users', name: 'users_list')]
    public function users(): Response
    {
        return $this->render('admin/users/users_list.html.twig', [
            'controller_name' => 'AdminController',
            'users' => $this->storageService->selectAllUsers()
        ]);
    }

    #[Route('/users/edit/{id}', name: 'edit_user')]
    public function editUser(int $id, Request $request): Response
    {
        $user = $this->storageService->selectUser($id);
        $form = $this->createForm(EditUserType::class, ['login' => $user['login'], 'storage_list_id' =>  $user['storage_list_id']], ['storages' => $this->storageService->prepareStorageArrayForSelect()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->storageService->updateUserStorage($data['login'], $data['storage_list_id']);

            return $this->redirectToRoute('users_list');
        }

        return $this->render('admin/users/edit_user.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
