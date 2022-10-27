<?php

namespace App\Twig\Extension;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TablePaginationExtension extends AbstractExtension
{
    private Environment $template;
    private RequestStack $request;

    public function __construct(Environment $template, RequestStack $requestStack)
    {
        $this->template = $template;
        $this->request = $requestStack;
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('tablePagination', [$this, 'tablePaginationFunction'], ['is_safe' => ['html']])
        ];
    }

    /**
     * @param Paginator $paginator
     * @param string $get
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function tablePaginationFunction(Paginator $paginator, string $get = 'page'): string
    {
        $request = $this->request->getCurrentRequest();
        $pages = ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
        $page = $request->query->getInt($get, 1);

        if ($page > $pages) {
            $page = 1;
        }

        return $this->template->render('elements/paginations/table-pagination.html.twig', [
            'pages' => $pages,
            'page'  => $page,
            'get'   => $get
        ]);
    }
}
