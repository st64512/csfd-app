<?php
namespace App\Presenters;

use App\Model\MovieFacade;
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
        $form->addTextArea('description', 'Děj:')
            ->setRequired();
        $form->addText('photo', 'Odkaz na obrázek:')
            ->setRequired();
        $form->addText('poster', 'Odkaz na poster:')
            ->setRequired();
        $form->addText('genres', 'Žánry:')
            ->setRequired();
        $form->addText('directors', 'Režiséři:')
            ->setRequired();
        $form->addText('actors', 'Herci:')
            ->setRequired();

        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = [$this, 'movieFormSucceeded'];

        return $form;
    }

    public function movieFormSucceeded(array $data): void
    {
        $movieId = $this->getParameter('movieId');

        $data['genres'] = $this->solveStringToJsonString($data['genres']);
        $data['actors'] = $this->solveStringToJsonString($data['actors']);
        $data['directors'] = $this->solveStringToJsonString($data['directors']);

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

        $movieArray['genres'] = $this->solveArrayToString($movieArray, 'genres');
        $movieArray['actors'] = $this->solveArrayToString($movieArray, 'actors');
        $movieArray['directors'] = $this->solveArrayToString($movieArray, 'directors');

        $this->getComponent('movieForm')->setDefaults($movieArray);
    }

    private function solveArrayToString(array $data,string $key) : string
    {
        $dataString = "";
        $decodedJsonData = Json::decode($data[$key]);
        foreach ($decodedJsonData as $decodedData) {
            $dataString .= ' ' . $decodedData . ',';
        }
        return trim($dataString, ' ,');
    }
    private function solveStringToJsonString(string $dataString) : string {
        $data = explode(',' ,$dataString);
        foreach ($data as $key => $d) {
            $data[$key] = trim($d, ' ');
        }

        return Json::encode($data);
    }
}