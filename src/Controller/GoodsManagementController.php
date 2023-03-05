<?php

namespace App\Controller;

use App\Form\ArticleEntryType;
use App\Service\StorageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class GoodsManagementController extends AbstractController
{

    private StorageService $storageService;

    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    #[Route('/entry', name: 'app_entry_article')]
    public function entryArticle(Request $request, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticleEntryType::class, null, ['article' => $this->storageService->prepareArticlesList()]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            $file = $form->get('attachment')->getData();
            $filePath = NULL;

            if ($data['amount'] <= 0) {
                $this->addFlash('warning', 'Ilość musi wynosić co najmniej 1!');

                return $this->render('goods_management/article_entry.html.twig', [
                    'entryArticle' => $form->createView(),
                ]);
            }

            if ($file) {
                $destination = $this->getParameter('brochures_directory');
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$file->guessExtension();
                $file->move(
                    $destination,
                    $newFilename
                );
                $filePath = $destination.$newFilename;
            }

            $passedData = $this->storageService->entryArticle($data, $filePath);

            if ($passedData) {
                $this->addFlash('success', 'Przyjęto artykuł!');
                $form = $this->createForm(ArticleEntryType::class, null, ['article' => $this->storageService->prepareArticlesList()]);
            } else {
                $this->addFlash('warning', 'Error!');
            }
        }

        return $this->render('goods_management/article_entry.html.twig', [
            'entryArticle' => $form->createView(),
        ]);
    }
}
