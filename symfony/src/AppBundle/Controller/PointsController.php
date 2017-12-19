<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Point;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PointsController extends Controller
{
    /**
     * @Route("/points")
     * @Method("POST")
     */
    public function createPoint(Request $request)
    {
        $body = $request->getContent();
        $data = json_decode($body, true);

        $point = new Point();
        $route = new \AppBundle\Entity\Route();

        $point->setLatitude($data['latitude']);
        $point->setLongitude($data['longitude']);
        $point->setName($data['name']);
        $point->setRoute($route);

        $data = $this->serializePoint($point);

        $em = $this->getDoctrine()->getManager();
        $em->persist($point);
        $em->flush();

        return new JsonResponse($data, JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/points/{id}")
     * @Method("GET")
     */
    public function getPointById($id)
    {
        $point = $this->getDoctrine()
            ->getRepository('AppBundle:Point')
            ->find($id);

        if (!$point) {
            throw $this->createNotFoundException(sprintf(
                'No point found',
                $point
            ));
        }

        $data = $this->serializePoint($point);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/points")
     * @Method("GET")
     */
    public function getAllPoints()
    {
        $em = $this->getDoctrine()->getManager();
        $points = $em->getRepository('AppBundle:Point')
            ->findAll();

        $data = array('points' => array());
        foreach ($points as $point) {
            $data['points'][] = $this->serializePoint($point);
        }

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/points/{id}")
     * @Method("PUT")
     */
    public function updatePoint($id, Request $request)
    {
        $point = $this->getDoctrine()
            ->getRepository('AppBundle:Point')
            ->find($id);

        if (!$point) {
            throw $this->createNotFoundException(sprintf(
                'No point found',
                $point
            ));
        }

        $data = json_decode($request->getContent(), true);

        $point->setLatitude($data['latitude']);
        $point->setLongitude($data['longitude']);
        $point->setName($data['name']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($point);
        $em->flush();

        $data = $this->serializePoint($point);

        return new JsonResponse($data, JsonResponse::HTTP_OK);
    }

    /**
     * @Route("/points/{id}")
     * @Method("DELETE")
     */
    public function deletePoint($id)
    {
        $point = $this->getDoctrine()
            ->getRepository('AppBundle:Point')
            ->find($id);

        if ($point) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($point);
            $em->flush();
        }

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    private function serializePoint(Point $point)
    {
        return array(
            'latitude' => $point->getLatitude(),
            'longitude' => $point->getLongitude(),
            'name' => $point->getName()
        );
    }
}