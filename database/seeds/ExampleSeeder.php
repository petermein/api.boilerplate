<?php

declare(strict_types=1);


use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;
use LaravelDoctrine\ORM\Facades\EntityManager;

/**
 * Class ExampleSeeder
 */
class ExampleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $example = new \Api\Domain\Models\Example();
        $example->id = 1;
        $example->title = 'test title';
        $example->name = 'example name';

        //Get dirty repository

        /** @var EntityManager $em */
        $em = app()->make(EntityManagerInterface::class);
        $em->persist($example);
    }//end run()
}//end class
