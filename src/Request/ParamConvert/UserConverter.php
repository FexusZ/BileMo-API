<?php

namespace App\Request\ParamConverter;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Psr\Container\ContainerInterface;
/**
 * Class UserConverter
 * @package App\Request\ParamConverter
 */
class UserConverter implements ParamConverterInterface
{

    private SerializerInterface $serializer;
    private Security $security;
    private ContainerInterface $container;
    /**
     * UserConverter constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer, Security $security, ContainerInterface $container)
    {
        $this->serializer = $serializer;
        $this->security = $security;
        $this->container = $container;
    }

    /**
     * @param Request $request
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        if ($request->isMethod(Request::METHOD_GET) || $request->isMethod(Request::METHOD_DELETE)) {
            return;
        }
       
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->post($request, $configuration);
        } else {
            $user = $this->put($request, $configuration);
        }
        $request->attributes->set($configuration->getName(), $user);
    }

    /**
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === User::class;
    }

    public function post(Request $request, ParamConverter $configuration)
    {
        $user = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');
        $user->setClient($this->security->getUser());

        return $user;
    }

    public function put(Request $request, ParamConverter $configuration)
    {
        /*if (!$this->container->has('doctrine')) {
            throw new \LogicException('The DoctrineBundle is not registered in your application. Try running "composer require symfony/orm-pack".');
        }

        $body = $this->serializer->deserialize(
            $request->getContent(),
            $configuration->getClass(),
            'json'
        );

        dd($body);
        dd($request->getContent(), $this->container->get('doctrine')->getRepository('App:User')->getPutedUser($body->get('')));*/
        $user = $request->attributes->get($configuration->getName());
        $this->serializer->deserialize(
            $request->getContent(),
            $configuration->getClass(),
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );
        // dd($user);
        return $user;
    }
}
