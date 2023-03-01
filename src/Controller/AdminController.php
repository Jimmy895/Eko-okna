<?php

namespace App\Controller;

use App\Form\CreateArticleType;
use App\Form\CreateNewStorageType;
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


    #[Route('/create_new_storage', name: 'create_storage')]
    public function createStorage(Request $request): Response
    {
        $form = $this->createForm(CreateNewStorageType::class, null, ['employee' => $this->storageService->prepareUsersArrayForSelect()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->storageService->insertNewStorage($data);
        }

        return $this->render('admin/create_storage.html.twig', [
            'addStorageForm' => $form->createView(),
        ]);
    }



    #[Route('/storages', name: 'app_storages')]
    public function index(): Response
    {


        return $this->render('admin/storages_list.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/create_new_article', name: 'create_article')]
    public function createArticle(Request $request): Response
    {
        $form = $this->createForm(CreateArticleType::class, null, ['units' => $this->storageService->prepareUnitsArrayForSelect()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->storageService->insertNewArticle($data);
        }

        return $this->render('admin/create_article.html.twig', [
            'addArticleForm' => $form->createView(),
        ]);
    }
}
