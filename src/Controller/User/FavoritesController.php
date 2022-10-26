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

use App\Entity\Favorites;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FavoritesController extends AbstractController
{
    private const LINK = '/dictionary/oxford/entries?search=';
    protected $fileName = 'favorites.xlsx';
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    /**
     * @return Response
     */
    #[Route('/user/favorites', methods: 'GET', name: 'favorites')]
    public function favorites(): Response
    {
        return $this->render('pages/user/favorites.html.twig');
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

        $repository = $doctrine->getRepository(Favorites::class);
        $favorites = $repository->findOneBy(['word_id' => $data]);

        if(!$favorites) {
            $favorites = new Favorites();
            $favorites->setCount(1);
            $favorites->setCreatedAt();
        } else {
            $currentCount = $favorites->getCount();
            $favorites->setCount(++$currentCount);
            $favorites->setUpdatedAt();
        }

        $entityManager = $doctrine->getManager();

        $favorites->setWordId($data);
        $favorites->setUserId($user->getId());
        $entityManager->persist($favorites);
        $entityManager->flush();

        return new Response('The word was saved');
    }

    #[Route('/user/favorites/export', methods: 'GET', name: 'export.favorites')]
    public function exportFavorites(ManagerRegistry $doctrine)
    {
        $spreadsheet = new Spreadsheet();
        
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();

        $writer = new Xlsx($spreadsheet);

        $user = $this->security->getUser();

        $repository = $doctrine->getRepository(Favorites::class);
        $favorites = $repository->findBy(['user_id' => $user->getId()]);

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
