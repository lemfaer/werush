<?php

/**
 * @noinspection PhpUnused
 * @noinspection PhpDocSignatureInspection
 */

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\ORMException;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Service\Attribute\Required;

class UserController extends AbstractController
{
    #[Required]
    public UserRepository $repository;

    #[Route('/')]
    public function index(): Response
    {
        return $this->redirect($this->getParameter('app.homepage'));
    }

    #[Route('/hello')]
    public function hello(): Response
    {
        return $this->response('hello')->setStatusCode(Response::HTTP_I_AM_A_TEAPOT);
    }

    /**
     * Get list of users
     */
    #[Route('/api/users', methods: ['GET'])]
    public function getUsersAction(Request $request): Response
    {
        $search = $request->query->get('search') ?: null;
        $limit = abs($request->query->getInt('limit', 10));
        $offset = abs($request->query->getInt('offset'));

        $data = $this->repository->getList($search, $limit, $offset);

        return $this->response($data)->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Get one user by id
     */
    #[Route('/api/user/{id}', methods: ['GET'])]
    public function getUserAction(?User $entity): Response
    {
        $code = $entity ? Response::HTTP_OK : Response::HTTP_NOT_FOUND;

        return $this->response($entity)->setStatusCode($code);
    }

    /**
     * Create new or replace a user
     *
     * @ParamConverter("current", converter="doctrine.orm")
     * @ParamConverter("modified", converter="api.param")
     */
    #[Route('/api/user/{id}', defaults: ['id' => null], methods: ['POST', 'PUT'])]
    public function saveUserAction(?User $current, User $modified, bool $replace = true): Response
    {
        try {
            $this->repository->save($current, $modified, $replace);
            [$success, $message] = [true, 'saved'];
        } catch (Exception $e) {
            [$success, $message] = [false, $e->getMessage()];
        }

        return $this
            ->response(compact("success", "message"))
            ->setStatusCode($success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }

    /**
     * Create new or replace a user
     *
     * @ParamConverter("current", converter="doctrine.orm")
     * @ParamConverter("modified", converter="api.param")
     */
    #[Route('/api/user/{id}', methods: ['PATCH'])]
    public function patchUserAction(User $current, User $modified): Response
    {
        return $this->saveUserAction($current, $modified, false);
    }

    /**
     * Delete user
     */
    #[Route('/api/user/{id}', methods: ['DELETE'])]
    public function deleteUserAction(User $entity): Response
    {
        try {
            $this->repository->delete($entity);
            [$success, $message] = [true, 'deleted'];
        } catch (ORMException $e) {
            [$success, $message] = [false, $e->getMessage()];
        }

        return $this
            ->response(compact("success", "message"))
            ->setStatusCode($success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST);
    }
}
