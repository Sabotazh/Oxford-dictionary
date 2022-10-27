<?php

namespace App\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Entity\Favorite;
use Symfony\Component\HttpFoundation\StreamedResponse;

use App\Repository\FavoriteRepository;

class FavoritesController extends AbstractController
{
    private $security;
    private const LINK = '/dictionary/oxford/entries?search=';
    protected $fileName = 'favorites.xlsx';
    private FavoriteRepository $favoriteRepository;

    /**
     * @var Security
     * @var FavoriteRepository
     */
    public function __construct(
        Security $security,
        FavoriteRepository $favoriteRepository
    )
    {
       $this->security = $security;
       $this->favoriteRepository = $favoriteRepository;
    }
    /**
     * @return Response
     */
    #[Route('/user/favorites', methods: 'GET', name: 'favorites')]
    public function favorites(): Response
    {
        $user = $this->security->getUser();
        $favorites = $this->favoriteRepository->findBy(['user_id' => $user->getId()]);
        return $this->render('pages/user/favorites.html.twig', [
            'favorites' => $favorites
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    #[Route('/user/favorite/add', methods: 'POST', name: 'add.favorite')]
    public function addFavorite(ManagerRegistry $doctrine, Request $request): Response
    {
        $user = $this->security->getUser();

        $data = $request->request->get('favorite');

        $favorite = $this->favoriteRepository->findOneBy(['word_id' => $data]);

        if(!$favorite) {
            $favorite = new Favorite();
            $favorite->setCount(1);
            $favorite->setCreatedAt();
        } else {
            $currentCount = $favorite->getCount();
            $favorite->setCount(++$currentCount);
            $favorite->setUpdatedAt();
        }

        $entityManager = $doctrine->getManager();

        $favorite->setWordId($data);
        $favorite->setUserId($user->getId());
        $entityManager->persist($favorite);
        $entityManager->flush();

        return new Response('The word was saved');
    }

    /**
     * @param int $id
     * @return Response
     */
    #[Route('/user/favorite/delete/{id}', methods: 'DELETE', name: 'delete.favorite')]
    public function deleteFavorite(int $id, ManagerRegistry $doctrine): Response
    {
        $favorite = $this->favoriteRepository->find($id);
        $this->favoriteRepository->remove($favorite);
        $entityManager = $doctrine->getManager();
        $entityManager->flush();
        return new Response('The word was deleted');
    }

    #[Route('/user/favorites/export', methods: 'GET', name: 'export.favorites')]
    public function exportFavorites()
    {
        $spreadsheet = new Spreadsheet();
        
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);

        $user = $this->security->getUser();

        $favorites = $this->favoriteRepository->findBy(['user_id' => $user->getId()]);

        $sheet->setTitle("My favorites words");

        foreach ($favorites as $key => $value) {
            $index = ++$key;
            $sheet->setCellValue('A' . $index, $value->getWordId());
            $sheet->setCellValue('B' . $index, $_SERVER['SERVER_NAME'] . self::LINK . $value->getWordId());
        }

        return $this->generateFile($writer, $this->fileName);
    }

    public function generateFile($writer, $fileName) {
        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );
        $response->headers->set('Content-Type', 'application/vnd.ms-excel');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control','max-age=0');
        return $response;
    }
}
