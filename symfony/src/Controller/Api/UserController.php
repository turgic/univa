<?php

namespace App\Controller\Api;

use App\Exceptions\ValidationException;
use App\Service\UserDataService;
use App\Validators\UserValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    public function __construct(
        private readonly UserDataService $userDataService,
        private readonly UserValidator $userValidator
    ) {

    }

    /**
     * Store user
     *
     * @param Request $request
     * @return Response
     */
    #[Route('/api/users/new', name: 'api_user_new', methods: ['POST'])]
    public function new(
        Request $request,
    ): Response {
        try {
            if (!$this->isGranted('ROLE_ADMIN')) {
                throw new AccessDeniedException('Access denied.');
            }
            $this->userDataService->createUser($this->userValidator->validate($request->getContent()));

            return $this->json(['message' => 'User created successfully'], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getErrors(), 422);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Edit user
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    #[Route('/api/users/{id}/edit', name: 'api_user_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): Response
    {
        try {
            $user = $this->getUser();
            if (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $id) {
                throw new AccessDeniedException('Access denied.');
            }
            $this->userDataService->editUser($id, $this->userValidator->validate($request->getContent()));

            return $this->json(['message' => 'User edited successfully'], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return new JsonResponse($e->getErrors(), 422);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], 500);
        }
    }

    /**
     * Get user
     *
     * @param int $id
     * @return Response
     */
    #[Route('/api/users/{id}', name: 'api_user_details', methods: ['GET'])]
    public function getUserDetails(int $id): Response
    {
        try {
            $user = $this->getUser();
            if (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $id) {
                throw new AccessDeniedException('Access denied.');
            }
            $user = $this->userDataService->getUserById($id);
            return $this->json(['message' => $user], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete user
     *
     * @param int $id
     * @return Response
     */
    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function deleteUser(int $id): Response
    {
        try {
            $user = $this->getUser();
            if (!$this->isGranted('ROLE_ADMIN') && $user->getId() !== $id) {
                throw new AccessDeniedException('Access denied.');
            }
            return $this->json(['message' => 'User deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}