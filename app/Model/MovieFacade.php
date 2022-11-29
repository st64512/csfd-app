<?php
namespace App\Model;

use Nette;

final class MovieFacade
{
    use Nette\SmartObject;

    private Nette\Database\Explorer $database;
    private string $databaseName = 'movies';

    public function __construct(Nette\Database\Explorer $database)
    {
        $this->database = $database;
    }

    public function getMovies(int $limit, int $offset)
    {
        return $this->database
            ->table($this->databaseName)
            ->order('id DESC')
            ->limit($limit, $offset);
    }

    public function getMovie(int $id): ?Nette\Database\Table\ActiveRow
    {
        return $this->database
            ->table($this->databaseName)
            ->get($id);
    }

    public function addMovie(array $data) : Nette\Database\Table\ActiveRow
    {
        return $this->database
            ->table($this->databaseName)
            ->insert($data);
    }

    public function editMovie(int $id, array $data): ?Nette\Database\Table\ActiveRow
    {
        $movie = $this->getMovie($id);
        $movie->update($data);
        return $movie;
    }

    public function getMoviesCount() : int
    {
        return $this->database->fetchField('SELECT COUNT(*) FROM movies');
    }

    public function deleteMovie(int $id)
    {
        $this->database->table($this->databaseName)->where('id', $id)->delete();
    }
}