<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{

    public function updateJwtData(JWTCreatedEvent $event) {
        //Recuperation de l'utilisateur 
        $user = $event->getUser();

        //Surchage des donnees du Token 
        $data =$event->getData();
        $data['id'] = $user->getId();
        // Revoie des donnees du Token 

        $event->setData($data);

    }
}