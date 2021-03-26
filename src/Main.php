<?php

namespace App;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;

class Main
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        return $this->serializer = $serializer;
    }

    public function getAllUser($repo)
    {
        $data = $repo->findBy(['archive' => false]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["user:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }
    public function getSolde($repo, $id){
        $data  = $repo->findOneBy(['user' => $id]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["compte:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }
    public function getOneUser($repo, $id)
    {
        $data = $repo->findOneBy(['id' => $id]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["user:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }

    public function getDeleted($repo)
    {
        $data = $repo->findBy(['archive' => true]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["user:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }

    public function getAllProfil($repo)
    {
        $data = $repo->findBy(['archive' => false]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["profil:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }

    public function getProfilDeleted($repo)
    {
        $data = $repo->findBy(['archive' => true]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["profil:read"]
        ]);
        return new JsonResponse($dataJson, Response::HTTP_CREATED, [], true);
    }

    public function getTransactionByCode($repo, $code)
    {
        $data = $repo->findBy(['codeTransaction' => $code]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["transaction:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);
    }

    public function getAllPart($repo)
    {
        $data = $repo->findBy(['archive' => true]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["part:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);
    }

    public function getAllAgence($repo)
    {
        $data = $repo->findBy(['status' => false]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["agence:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);
    }

    public function showAllCaissier($repo)
    {
        $data = $repo->findBy(['archive' => false]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["caissier:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);
    }

    public function getPartAgence($repo, $id)
    {
        $data = $repo->findBy(['archive' => true], ['compte_id' => $id]);
        $dataJson = $this->serializer->serialize($data,     "json", [
            "groups" => ["part:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);
    }

    public function getTransactionById($repo, $id)
    {
        $data = $repo->findBy(['user' => $id]);
        $dataJson = $this->serializer->serialize($data, "json", [
            "groups" => ["info:read"]
        ] );
        return new JsonResponse($dataJson, Response::HTTP_ACCEPTED, [], true);

    }
    
}
