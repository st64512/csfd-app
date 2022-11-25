<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\MovieFacade;
use Nette;


final class HomepagePresenter extends Nette\Application\UI\Presenter
{
    private MovieFacade $facade;

    public function __construct(MovieFacade $facade)
    {
        $this->facade = $facade;
    }

    public function renderDefault() : void
    {
        $this->template->movies = $this->facade
            ->getMovies()
            ->limit(5);
    }
}
