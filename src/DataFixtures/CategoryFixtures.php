<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // arm => gauntlet, bracer, glove
        // leg =>  greave, boot, shoe
        // chest => chestplate, jacket, vest
        // head => hat, helmet

        $categories = [
            'arms' => ['gauntlet', 'bracer', 'glove'],
            'legs' => ['greave', 'boot', 'shoe'],
            'chest' => ['chestplate', 'jacket', 'vest'],
            'head' => ['hat', 'helmet']
        ];

        foreach ($categories as $categoryName => $subcategories) {
            $category = new Category();
            $category->setName($categoryName);
            foreach ($subcategories as $subCatName) {
                $subCategory = new Category();
                $subCategory->setName($subCatName);
                $subCategory->setParent($category);
                $manager->persist($subCategory);
            }
            $manager->persist($category);
        }
        $manager->flush();
    }
}