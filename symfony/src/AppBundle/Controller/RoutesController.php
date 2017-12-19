<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RoutesController extends Controller
{
    /**
     * @Route("/routes")
     * @Method("POST")
     */
    public function createRoute(Request $request)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $route = new \AppBundle\Entity\Route();

        $route->setName($data['name']);

        $points = $data['points'];
//        $pointsArray = [];
//        foreach ($points as $point) {
////            $pointsArray[] = $this->pointService->createPoint(
////                $route,
////                (float)$point['latitude'],
////                (float)$point['longitude'],
////                $point['name'],
////                $point['address']);
//            $pointsArray->setPoints($point);
//        }
        $route->setPoints($points);

        $em = $this->getDoctrine()->getManager();
        $em->persist($route);
        $em->flush();

        return new JsonResponse($route, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/routes")
     * @Method("GET")
     */
    public function getAllRoutes()
    {
        return new JsonResponse('get all Routes');
    }

    /**
     * @Route("/routes/{id}")
     * @Method("GET")
     */
    public function getRouteById($id)
    {
        return new JsonResponse('get Route by id ' . $id);
    }

    /**
     * @Route("/routes/{id}")
     * @Method("PUT")
     */
    public function updateRoute($id)
    {
        return new JsonResponse('update Route ' . $id);
    }

    /**
     * @Route("/routes/{id}")
     * @Method("DELETE")
     */
    public function deleteRoute($id)
    {
        return new JsonResponse('delete Route ' . $id);
    }
}