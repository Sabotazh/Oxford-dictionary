<?php

namespace App\Controller\User;

use App\Entity\Favorite;
use App\Exception\FavoriteException;
use App\Repository\FavoriteRepository;
use App\Repository\SearchRepository;
use App\Service\FavoriteService;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FavoritesController extends AbstractController
{
    private const LINK = '/dictionary/oxford/entries?search=';

    protected string $fileName = 'favorites.xlsx';

    private Security $security;
    private FavoriteRepository $favoriteRepository;

    public function __construct(Security $security, FavoriteRepository $favoriteRepository)
    {
       $this->security = $security;
       $this->favoriteRepository = $favoriteRepository;
    }
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/user/favorites', methods: 'GET', name: 'user_favorites')]
    public function favorites(): Response
    {
        $user = $this->security->getUser();
        $favorites = $this->favoriteRepository->findBy(['user_id' => $user->getId()]);
        return $this->render('pages/user/favorites.html.twig', [
            'favorites' => $favorites
        ]);
    }

    /**
     * @param ManagerRegistry $doctrine
     * @param Request $request
     * @param SearchRepository $searcheRepository
     * @param FavoriteService $service
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/user/favorite/add', methods: 'POST', name: 'user_favorite_add')]
    public function addFavorite(
        ManagerRegistry  $doctrine,
        Request          $request,
        SearchRepository $searcheRepository,
        FavoriteService  $service
    ): JsonResponse
    {
        $word = $request->request->get('favorite');
        $user = $this->security->getUser();
        $history = $searcheRepository->findOneBy(['word' => $word]);

        // check if this word is in the favorites, if so, throw an exception
        try {
            $service->isExists($user->getId(), $history->getId());
        } catch (FavoriteException $exception) {
            return new JsonResponse([
                'status' => 'failed',
                'code' => Response::HTTP_BAD_REQUEST,
                'exception' => $exception,
                'message' => $exception->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        // add word to favorites table
        try {
            $entityManager = $doctrine->getManager();

            $favorite = new Favorite();
            $favorite->setUserId($user->getId())
                ->setWordId($history->getId())
                ->setCreatedAt()
                ->setUpdatedAt();

            $entityManager->persist($favorite);
            $entityManager->flush();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'status' => 'error',
                'code' => Response::HTTP_BAD_REQUEST,
                'exception' => $exception,
                'message' => 'Some error has occurred with save favorite.',
            ], Response::HTTP_BAD_REQUEST);
        }

        // return success result
        return new JsonResponse([
            'status'    => 'success',
            'code'      => Response::HTTP_OK,
            'message'   => 'New word added to your favorites.',
        ], Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
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

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    #[Route('/user/favorites/export', methods: 'GET', name: 'export.favorites')]
    public function exportFavorites(): StreamedResponse
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

    /**
     * @param $writer
     * @param $fileName
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function generateFile($writer, $fileName): StreamedResponse
    {
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
