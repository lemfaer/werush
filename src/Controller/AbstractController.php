<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseController;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends BaseController
{
    /**
     * Return REST API response
     *
     * @param mixed $data data to return
     *
     * @return Response
     */
    protected function response($data = null): Response
    {
        return $this->json($data);
    }
}
