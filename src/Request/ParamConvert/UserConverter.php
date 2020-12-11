<?php

namespace App\Request\ParamConverter;

use App\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

use Psr\Container\ContainerInterface;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Class UserConverter
 * @package App\Request\ParamConverter
 */
class UserConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;
    private Security $security;
    private ContainerInterface $container;
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $encoder;

    /**
     * UserConverter constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, Security $security, ContainerInterface $container, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->serializer = $serializer;
        $this->security = $security;
        $this->container = $container;
        $this->em = $em;
        $this->encoder = $encoder;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        if ($request->attributes->has("id")) {
            $this->hasId($request, $configuration);
        }

        if ($request->isMethod(Request::METHOD_GET) || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }
       
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->post($request, $configuration);
        } else {
            $user = $this->put($request, $configuration);
        }

        $user->setPassword($this->encoder->encodePassword($user->getReseller(), $user->getPassword()));
        $request->attributes->set($configuration->getName(), $user);
    }

    /**
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === User::class;
    }

    public function post(Request $request, ParamConverter $configuration): User
    {
        $user = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');
        $user->setReseller($this->security->getUser());

        return $user;
    }

    public function put(Request $request, ParamConverter $configuration): User
    {
        $user = $request->attributes->get($configuration->getName());

        $this->serializer->deserialize(
            $request->getContent(),
            $configuration->getClass(),
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );

        return $user;
    }

    public function hasId(Request $request, ParamConverter $configuration): void
    {
        $user = $this->em
            ->getRepository($configuration->getClass())
            ->find($request->attributes->get("id"))
        ;

        if (!$user || $user->getReseller() !== $this->security->getUser()) {
            throw new Exception\NotFoundHttpException('Cannot access, User not found');
        }

        $request->attributes->set($configuration->getName(), $user);
    }
}