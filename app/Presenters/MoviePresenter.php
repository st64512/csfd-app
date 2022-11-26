<?php
namespace App\Presenters;

use App\Model\MovieFacade;
use App\Utility\Utilities;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Json;

final class MoviePresenter extends Nette\Application\UI\Presenter
{
    private MovieFacade $facade;

    public function __construct( MovieFacade $facade)
    {
        $this->facade = $facade;
    }

    public function renderDetail(int $movieId): void
    {
        $movie = $this->facade->getMovie($movieId);

        if (!$movie) {
            $this->error('Film nebyl nalezen');
        }

        $this->template->movie = $movie;
        $this->template->genres = Json::decode($movie->genres);
        $this->template->actors = Json::decode($movie->actors);
        $this->template->directors = Json::decode($movie->directors);
    }

    protected function createComponentMovieForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Název:')
            ->setRequired();
        $form->addInteger('year', 'Rok:')
            ->setRequired();
        $form->addTextArea('description', 'Děj:');
        $form->addText('photo', 'Odkaz na obrázek:');
        $form->addText('poster', 'Odkaz na poster:');
        $form->addText('genres', 'Žánry:');
        $form->addText('directors', 'Režiséři:');
        $form->addText('actors', 'Herci:');

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = [$this, 'movieFormSucceeded'];

        return $form;
    }

    public function movieFormSucceeded(array $data): void
    {
        $movieId = $this->getParameter('movieId');

        $data['genres'] = Utilities::solveStringToJsonString($data['genres']);
        $data['actors'] =  Utilities::solveStringToJsonString($data['actors']);
        $data['directors'] =  Utilities::solveStringToJsonString($data['directors']);

        if ($movieId) {
            $movie = $this->facade->editMovie($movieId, $data);
            $this->flashMessage("Film byl úspěšně editován.", 'success');
        } else {
            $movie =  $this->facade->addMovie($data);
            $this->flashMessage("Film byl úspěšně přidán.", 'success');
        }

        $this->redirect('Movie:detail', $movie->id);
    }

    public function renderEdit(int $movieId) : void
    {
        $movie = $this->facade->getMovie($movieId);

        if (!$movie) {
            $this->error('Film nebyl nalezen.');
        }

        $movieArray = $movie->toArray();

        $movieArray['genres'] =  Utilities::solveArrayToString($movieArray, 'genres');
        $movieArray['actors'] =  Utilities::solveArrayToString($movieArray, 'actors');
        $movieArray['directors'] =  Utilities::solveArrayToString($movieArray, 'directors');

        $this->getComponent('movieForm')->setDefaults($movieArray);
    }
}