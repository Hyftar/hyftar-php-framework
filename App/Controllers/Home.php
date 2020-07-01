<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller
{
    protected $current_page = 1;

    public function indexAction()
    {
        $pageValidator = new \Core\Validator(
            [$this, 'firstPageValidator'],
            [$this, 'secondPageValidator'],
            [$this, 'thirdPAgeValidator']
        );

        $pageValidator->addOnValidCallback([$this, 'setPage']);

        $page = $this->route_params['variables']['page'] ?? null;

        $pageValidator->validate($page);

        $this->renderTemplate('Home/index.html.twig', ['page' => $this->current_page]);
    }

    public function firstPageValidator($page)
    {
        return $page != null;
    }

    public function secondPageValidator($page)
    {
        return $page > 0;
    }

    public function thirdPAgeValidator($page)
    {
        return $page < 5;
    }

    public function setPage($page)
    {
        $this->current_page = $page;
    }
}
