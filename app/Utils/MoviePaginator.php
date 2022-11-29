<?php

namespace App\Utils;

use Nette\Utils\Paginator;

class MoviePaginator extends Paginator
{
    public function getNearPages()
    {
        $visiblePages = [];
        for ($i = $this->getPage() - 2; $i <=  $this->getPage() + 2; $i++) {
            if ($i == $this->getPage()
                || ($i > 1 && $i < $this->getPageCount())) {
                $visiblePages[] = $i;
            }
        }
        return $visiblePages;
    }

}