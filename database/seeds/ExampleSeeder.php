<?php

declare(strict_types=1);


use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Database\Seeder;

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

        /** @var EntityManagerInterface $em */
        $em = app()->make('em');
        $em->persist($example);
        $em->flush();
    }//end run()
}//end class
