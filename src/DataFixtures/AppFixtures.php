<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Promocode;
use App\Entity\TaxNumber;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $iphone = new Product();
        $iphone->setName('Iphone');
        $iphone->setPrice(100);
        $manager->persist($iphone);

        $headphones = new Product();
        $headphones->setName('Наушники');
        $headphones->setPrice(20);
        $manager->persist($headphones);

        $case = new Product();
        $case->setName('Чехол');
        $case->setPrice(10);
        $manager->persist($case);

        $p10 = new Promocode();
        $p10->setName('P10');
        $p10->setType('percent');
        $manager->persist($p10);

        $p50 = new Promocode();
        $p50->setName('P50');
        $p50->setType('percent');
        $manager->persist($p50);

        $f15 = new Promocode();
        $f15->setName('F15');
        $f15->setType('fixed');
        $manager->persist($f15);


        $germany = new TaxNumber();
        $germany->setCountry('Германия');
        $germany->setPercent('19');
        $germany->setMask('DEXXXXXXXXX ');
        $manager->persist($germany);

        $france = new TaxNumber();
        $france->setCountry('Франция');
        $france->setPercent('20');
        $france->setMask('FRYYXXXXXXXXX');
        $manager->persist($france);

        $manager->flush();
    }
}
