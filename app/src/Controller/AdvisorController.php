<?php

namespace App\Controller;

use App\Repository\AdvisorRepository;
use App\Service\AdvisorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Advisor controller - CRUD for Advisor entity
 * @Route("/advisor", name="advisor_")
 * @package App\Controller
 */
class AdvisorController extends AbstractController
{
    /**
     * @var AdvisorRepository
     */
    private $advisorRepository;
    /**
     * @var AdvisorService
     */
    private $advisorService;

    public function __construct(
        AdvisorRepository $advisorRepository,
        AdvisorService $advisorService
    )
    {
        $this->advisorRepository = $advisorRepository;
        $this->advisorService = $advisorService;
    }

    /**
     * Advisor list
     *  GET parameters:
     *  filter: allows to filter on name (sadly not with language)
     *          example: ?filter[name]=Madam Indigo
     *  limit: number of records in the page
     *  order: field to sort records
     *          example: ?order[pricePerMinute]=ASC
     *  page: number of requested page (skips (page-1)*limit records)
     * @Route("", name="get_list", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $params = $request->query->all();
        $advisors = $this->advisorRepository->findBy(
            $params['filter'] ?? [],
            $params['order'] ?? null,
            $params['limit'] ?? null,
            isset($params['page'], $params['limit']) ? ($params['page'] - 1) * $params['limit'] : null
        );
        return $this->json($advisors);
    }

    /**
     * Gets single Advisor records based on provided ID
     * @Route("/{id}", name="get", methods={"GET"})
     * @param int $id
     * @return Response
     */
    public function getAdvisor(int $id): Response
    {
        $advisor = $this->advisorRepository->find($id);
        if (!$advisor) {
            return new Response("Not found", 404);
        }
        return new JsonResponse($advisor);
    }

    /**
     * Creates unavailable Advisor. form-data body type. image may be added on 'image field'
     * @Route("", name="create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function postAdvisor(Request $request): Response
    {
        $data = $request->request->all();
        $data['image'] = $request->files->get('image');
        try {
            $advisor = $this->advisorService->createAdvisor($data);
        } catch (BadRequestException $e) {
            return new Response($e->getMessage(), 400);
        }
        return new JsonResponse($advisor);
    }

    /**
     * Updates Advisor based on ID. See (POST)/advisor
     * @Route("/{id}", name="update", methods={"POST"})
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function patchAdvisor($id, Request $request): Response
    {
        $data = $request->request->all();
        $data['image'] = $request->files->get('image');
        $advisor = $this->advisorRepository->find($id);
        try {
            $this->advisorService->updateAdvisor($advisor, $data);
        } catch (BadRequestException $e) {
            return new Response($e->getMessage(), 400);
        }

        return $this->json($advisor);
    }

    /**
     * Deletes Advisor based on ID
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param $id
     * @return Response
     */
    public function deleteAdvisor($id): Response
    {
        $advisor = $this->advisorRepository->find($id);
        $this->advisorService->deleteAdvisor($advisor);
        return new JsonResponse(["deleted" => 1]);
    }

    /**
     * Returns Advisor image if available. 404 if no image
     * @Route("/{id}/image", name="get_image", methods={"GET"})
     *
     */
    public function getAdvisorImage($id): Response
    {
        $advisor = $this->advisorRepository->find($id);
        if (!$advisor->getImage()) {
            return new Response('Not found', 404);
        }
        $response = new Response($advisor->getImage());
        $response->headers->add(['Content-Type' => $advisor->getImageMime()]);
        return $response;
    }
}
