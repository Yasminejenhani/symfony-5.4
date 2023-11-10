<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/showauthor/{name}', name: 'app_showauthor')]
    public function showAuthor($name): Response
    {
        return $this->render('Author/showAuthor.html.twig', [
            'nameAuthor' => $name, 
         ]);
        }

        #[Route('/listauthor', name: 'list_author')]
    public function list()
    {
        $authors = array(
            array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100, 'photo' => '/images/victor-hugo.jpg'),
            array ('id' => 2, 'username' => 'William Shakespeare','email'=>'william.shakespeare@gmail.com','nb_books' => 200, 'photo' => '/images/william-shakespeare.jpg'),
            array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300, 'photo'=> '/images/taha-hussein.jpg'),
        );

                        return $this->render("Author/list.html.twig",
                        ['tabAuthors'=>$authors]);
    }
    #[Route('/authorDetails/{id}', name: 'author_details')]
    public function authorDetail($id)
    {
        $authors = array(
            array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100, 'photo' => '/images/victor-hugo.jpg'),
            array ('id' => 2, 'username' => 'William Shakespeare','email'=>'william.shakespeare@gmail.com','nb_books' => 200, 'photo' => '/images/william-shakespeare.jpg'),
            array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300, 'photo'=> '/images/taha-hussein.jpg'),
        );

        $author = null;

        // Loop through the authors array to find the author with the matching ID
        foreach ($authors as $auth) {
            if ($auth["id"] == $id) {
                $author = $auth;
                break; // Exit the loop once the author is found
            }
        }

        // Check if the author was found
        if ($author === null) {
            throw $this->createNotFoundException('Author not found');
        }

        return $this->render('Author/authorDetail.html.twig', ["author" => $author]);
    }
    #[Route('/listauthors', name: 'list_authors')]
    public function listAuthor(AuthorRepository $repository)
    {
        $authors= $repository->findAll();
        return $this->render("Author/author.html.twig"
        ,array('tabAuthors'=>$authors));
    }

   // #[Route('/authorDetails/{id}', name: 'author_details')]
       #[Route('/addauthor', name: 'add_author')]
    public function addAuthor(ManagerRegistry $managerRegistry)
    {
        $author= new Author();
        $author->setUsername("author4");
        $author->setEmail("author4@gmail.com");
        $author->setDescription("test");
        $author->setTest("test");
        #1ere method
        #$em= $this->getDoctrine()->getManager();
        #2methode
        $em= $managerRegistry->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute("list_authors");
    }
    
    #[Route('/add', name: 'add')]
    public function add(Request $request,ManagerRegistry $managerRegistry)
    {
        $author= new Author();
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em= $managerRegistry->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("list_authors");
        }
        //     1ère methode
        /*        return $this->render("author/add.html.twig",
                    array('authorForm'=>$form->createView()));*/
// 2ème méthode
            return $this->renderForm("author/add.html.twig",
            array('authorForm'=>$form));
            }


     #[Route('/delete/{id}', name: 'delete')]
    //  #[Method('POST')]
             public function deleteAuthor($id,AuthorRepository $repository,ManagerRegistry $managerRegistry)
            {
                $author= $repository->find($id);
                $em= $managerRegistry->getManager();
                $em->remove($author);
                $em->flush();
                return $this->redirectToRoute("list_authors");
            }

            #[Route('/update/{id}', name: 'update_author')]
            public function update(ManagerRegistry $managerRegistry,$id,AuthorRepository $repository)
            {
                $author=$repository->find($id);
                $author->setUsername("yass");
                $author->setEmail("yass@esprit.tn");
                $author->setDescription("test");
                $em= $managerRegistry->getManager();
                $em->flush();
                return $this->redirectToRoute("list_authors");
            }

            #[Route('/edit/{id}', name: 'edit')]
            public function edit(AuthorRepository $repository, $id , Request $request,ManagerRegistry $managerRegistry){
                $author = $repository-> find($id);
                $form=$this->createForm(AuthorType::class,$author);
                $form->add('edit',SubmitType::class);
                $form->handleRequest($request);
                if ($form->isSubmitted()){
                    $em=$managerRegistry->getManager();
                    $em->flush();
                    return $this->redirectToRoute("list_authors");
                }
                return $this->render('Author/edit.html.twig',['authorForm'=>$form->createView()]);
            }
           
        }
