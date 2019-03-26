<?php

namespace AppBundle\Command;

use Symfony\Component\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Question\Question;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

class CreateProductCommand extends ContainerAwareCommand{

    protected function configure(){
        $this
        ->setName('app:add-product')
        ->setDescription('Create new product')
        ->setDefinition(array(
            new InputArgument('name', InputArgument::REQUIRED, 'Product name'),
            new InputArgument('category_id', InputArgument::REQUIRED, 'Product category'),
            new InputArgument('description', InputArgument::REQUIRED, 'Product description'),
            new InputArgument('price', InputArgument::REQUIRED, 'Product price'),
            new InputArgument('stock', InputArgument::REQUIRED, 'Product stock')
        ))
        ->setHelp('This command is used to create new product category');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $category = new Category();
        $product = new Product();

        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();

        $product->setName($input->getArgument('name'));
        $product->setCategoryId($input->getArgument('category_id'));
        $product->setDescription($input->getArgument('description'));
        $product->setPrice($input->getArgument('price'));
        $product->setStock($input->getArgument('stock'));
        $product->setQtyHold(0);
        $product->setAvailableQty($input->getArgument('stock'));


        $entityManager->persist($product);
        $entityManager->flush();
    }

    protected function interact(InputInterface $input, OutputInterface $output){
        $questions = array();

        if(!$input->getArgument('name')){
            $question = new Question("Enter product name: ");
            $question->setValidator(function($name){
                if(empty($name)){
                    throw new \Exception('Product name can not be empty');
                }
                return $name;
            });
            $questions['name'] = $question;
        }

        if(!$input->getArgument('category_id')){
            $question = new Question("Enter product's category id: ");
            $question->setValidator(function($category_id){
                if(empty($category_id)){
                    throw new \Exception('Category id can not be empty');
                }
                return $category_id;
            });
            $questions['category_id'] = $question;
        }

        if(!$input->getArgument('description')){
            $question = new Question("Enter product description: ");
            $question->setValidator(function($description){
                if(empty($description)){
                    throw new \Exception('Product description can not be empty');
                }
                return $description;
            });
            $questions['description'] = $question;
        }

        if(!$input->getArgument('price')){
            $question = new Question("Enter product price: ");
            $question->setValidator(function($price){
                if(empty($price)){
                    throw new \Exception('Product price can not be empty');
                }
                return $price;
            });
            $questions['price'] = $question;
        }

        if(!$input->getArgument('stock')){
            $question = new Question("Enter product stock: ");
            $question->setValidator(function($stock){
                if(empty($stock)){
                    throw new \Exception('Product stock can not be empty');
                }
                return $stock;
            });
            $questions['stock'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}