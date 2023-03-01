<?php

namespace App\Controller;

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



    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {


        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
}
