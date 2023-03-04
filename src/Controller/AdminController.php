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

class AdminController extends AbstractController
{
    private StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    #[Route('/storage/create', name: 'create_storage')]
    public function createStorage(Request $request): Response
    {
        $form = $this->createForm(CreateNewStorageType::class, null, ['employee' => $this->storageService->prepareUsersArrayForSelect(), 'create' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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

    #[Route('/storage/edit/{id}', name: 'edit_storage')]
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

    #[Route('/user/edit/{id}', name: 'edit_user')]
    public function editUser(int $id, Request $request): Response
    {
        $user = $this->storageService->selectUser($id);
        $form = $this->createForm(EditUserType::class, ['login' => $user['login'], 'storages' => $this->storageService->prepareStorageArrayForSelect()]);
        $form->handleRequest($request);
        dd($user, $this->storageService->prepareStorageArrayForSelect());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
//            $this->storageService->updateStorageName($id, $data['name']);

            return $this->redirectToRoute('users_list');
        }

        return $this->render('admin/users/edit_user.html.twig', [
            'controller_name' => 'AdminController',
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }


    #[Route('/article/create', name: 'create_article')]
    public function createArticle(Request $request): Response
    {
        $form = $this->createForm(CreateArticleType::class, null, ['units' => $this->storageService->prepareUnitsArrayForSelect()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->storageService->insertNewArticle($data);
        }

        return $this->render('admin/articles/create_article.html.twig', [
            'addArticleForm' => $form->createView(),
        ]);
    }
}
