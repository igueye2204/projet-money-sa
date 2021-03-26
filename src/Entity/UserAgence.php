<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserAgenceRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=UserAgenceRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "path"   = "/adminagence/useragences",
 *          "method" = "get",
 *          "security" = "(is_granted('ROLE_ADMINAGENCE'))",
 *          "security_message" = "Accès refusé!"
 *     }
 *  },
 *  itemOperations = {
 *     "get",
 *      "bloque_useragence" = {
 *          "method" = "delete",
 *     "deserialize" = false,
 *        "security" = "(is_granted('ROLE_ADMINAGENCE'))",
 * "security_message" = "Accès refusé!"
 * }
 *  }
 * )
 */
class UserAgence extends User
{
    
}
