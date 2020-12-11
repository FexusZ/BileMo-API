<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\Reseller;
use App\Entity\User;
use App\Entity\Product;

class AppFixtures extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param $upload_directory
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $m =0;
        for ($i=1; $i <= 2; $i++) {
            $reseller = new Reseller;
            $reseller->setPassword($this->encoder->encodePassword($reseller, 'test'));
            $reseller->setEmail('test' . $i . '@email.fr');

            $manager->persist($reseller);
            $manager->flush();
            for ($j=1; $j <= 5; $j++) {
                $user = new User;
                $user->setUsername('test' . ($j + $m))
                    ->setEmail('test' . ($j + $m) . '@email.fr')
                    ->setPassword($this->encoder->encodePassword($reseller, 'test'))
                    ->setReseller($reseller)
                ;

                $manager->persist($user);
                $manager->flush();
            }
            $m=5;
        }

        for ($i=1; $i <= 10; $i++) {
            $product = new Product;
            $product->setName('Phone' . $i)
                ->setDescription('Description Phone' . $i)
                ->setPrice(($i % 2 == 0 ? 199.99 : 299.99))
                ->setYear(($i % 2 == 0 ? 2019 : 2020))
            ;
            $manager->persist($product);
            $manager->flush();
        }
    }
}