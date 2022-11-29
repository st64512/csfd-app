<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\MovieFacade;

use App\Utils\MoviePaginator;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private MovieFacade $facade;

    public function __construct(MovieFacade $facade)
    {
        $this->facade = $facade;
    }

    public function renderDefault(int $page = 1, int $itemsPerPage = 12) : void
    {
        $moviesCount = $this->facade->getMoviesCount();
        $paginator = new MoviePaginator();
        $paginator->setItemCount($moviesCount)
            ->setItemsPerPage($itemsPerPage)
            ->setPage($page);
        $this->template->movies = $this->facade->getMovies($paginator->getLength(), $paginator->getOffset());
        $this->template->paginator = $paginator;
        $this->template->itemsCount = [12, 24, 36];
        $this->template->itemsPerPage = $itemsPerPage;
    }
}
