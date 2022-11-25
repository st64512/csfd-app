<?php
namespace App\Presenters;

use App\Model\MovieFacade;
use Nette;
use Nette\Application\UI\Form;

final class EditMoviePresenter extends Nette\Application\UI\Presenter
{
    private MovieFacade $facade;

    public function __construct( MovieFacade $facade)
    {
        $this->facade = $facade;
    }

    protected function createComponentMovieForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Název:')
            ->setRequired();
        $form->addSubmit('send', 'Uložit změny');

        $form->onSuccess[] = [$this, 'movieFormSucceeded'];

        return $form;
    }

    public function movieFormSucceeded(array $data): void
    {
        $movieId = $this->getParameter('movieId');

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

        $this->getComponent('movieForm')->setDefaults($movie->toArray());
    }
}