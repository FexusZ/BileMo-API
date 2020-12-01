<?php

namespace App\Request\ParamConverter;

use App\Entity\Reseller;

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
 * Class ResellerConverter
 * @package App\Request\ParamConverter
 */
class ResellerConverter implements ParamConverterInterface
{
    private SerializerInterface $serializer;
    private Security $security;
    private ContainerInterface $container;
    private EntityManagerInterface $em;
    private UserPasswordEncoderInterface $encoder;

    /**
     * ResellerConverter constructor.
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
        if ($request->isMethod(Request::METHOD_GET) || $request->isMethod(Request::METHOD_DELETE) || $request->isMethod(Request::METHOD_PUT)) {
            return;
        }
        
        $reseller = $this->post($request, $configuration);
        $reseller->setPassword($this->encoder->encodePassword($reseller, $reseller->getPassword()));

        $request->attributes->set($configuration->getName(), $reseller);
    }

    /**
     * @param ParamConverter $configuration
     * @return bool|void
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Reseller::class;
    }

    public function post(Request $request, ParamConverter $configuration): Reseller
    {
        $reseller = $this->serializer->deserialize($request->getContent(), $configuration->getClass(), 'json');
        return $reseller;
    }
}