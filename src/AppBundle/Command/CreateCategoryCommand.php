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

class CreateCategoryCommand extends ContainerAwareCommand{

    protected function configure(){
        $this
        ->setName('app:create-category')
        ->setDescription('Create new category')
        ->setDefinition(array(
            new InputArgument('category', InputArgument::REQUIRED, 'New category')
        ))
        ->setHelp('This command is used to create new product category');
    }

    protected function execute(InputInterface $input, OutputInterface $output){
        $category = new Category();

        $entityManager = $this->getContainer()->get('doctrine')->getEntityManager();

        $category->setCategory($input->getArgument('category'));

        $entityManager->persist($category);
        $entityManager->flush();

    }

    protected function interact(InputInterface $input, OutputInterface $output){
        $questions = array();

        if(!$input->getArgument('category')){
            $question = new Question("Enter new category's name: ");
            $question->setValidator(function($category){
                if(empty($category)){
                    throw new \Exception('Category name can not be empty');
                }
                return $category;
            });
            $questions['category'] = $question;
        }

        foreach ($questions as $name => $question) {
            $answer = $this->getHelper('question')->ask($input, $output, $question);
            $input->setArgument($name, $answer);
        }
    }
}