<?php

namespace App\Controller;


use App\Entity\Book;
use App\Form\BookType;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;



class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    // #[Route('/listbooks', name: 'list_books')]
    // public function listBook(BookRepository $repository)
    // {
    //     $books= $repository->findAll(); //select*
    //     return $this->render("Book/listBooks.html.twig"
    //     ,array('tabBooks'=>$books));
    // }
    #[Route('/addbook', name: 'add_book')]
    public function addBook(Request $request,ManagerRegistry $managerRegistry):Response
    {
        $book= new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted()){
           // $book->setPublished('true');
            $nbrBooks= $book->getAuthor()->getNbrBooks();
            $book->getAuthor()->setNbrBooks($nbrBooks+1);
            $em= $managerRegistry->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute("list_books");
        }
        return $this->renderForm("book/add.html.twig",
        array('bookForm'=>$form));
        }

        #[Route('/listbooks', name: 'list_books')]
        public function listBook(BookRepository $repository)
        {
            $publishedBooks=$this->getDoctrine()->getRepository(Book::class)->findBy(['published'=>true]);
            //compter le nombre de livres publiés
            $nbPublishedBooks=count($publishedBooks);
            $nbUnpublishedBooks=count($this->getDoctrine()->getRepository(Book::class)->findBy(['published'=>false]));
            if($nbPublishedBooks>0){
                return $this->render('Book/listBooks.html.twig',['publishedBooks'=>$publishedBooks, 'nbUnpublishedBooks'=>$nbUnpublishedBooks, 'nbPublishedBooks'=>$nbPublishedBooks]);
            }
            else{
                return $this->render('Book/NoBooksFound.html.twig');
            }
        }
        #[Route('updateBook/{ref}',name:'updateB')]
        public function updateBook(BookRepository $repository, $ref, Request $request, ManagerRegistry $manager):Response
        {
            $book=$repository->find($ref);
            $form=$this->createForm(BookType::class, $book);
            //$form->add('Edit', SubmitType::class);
            $form->handleRequest($request);
            if($form->isSubmitted()){
                $em=$manager->getManager();
                $em->flush();
                return $this->redirectToRoute('list_books');
            }
        return $this->renderForm('book/updateBook.html.twig',
        ['form'=>$form]);
        }
        #[Route('deleteBook/{ref}',name:'deleteB')]
        public function deleteBook(BookRepository $repository, $ref, Request $request, ManagerRegistry $manager):Response
        {
            $book=$repository->find($ref);
            $em=$manager->getManager();
            $em->remove($book);
            $em->flush(); 
            return $this->redirectToRoute('list_books');
        }
        #[Route('detBook/{ref}',name:'detB')]
        public function details(BookRepository $repository, $ref, Request $request):Response
        {
            $book=$repository->find($ref);
             if(!$book){
                return $this->redirectToRoute('list_books');
             }   
            return $this->render('Book/show.html.twig',['b'=>$book]);
        }
    //     #[Route('/books-by-author/{authorId}', name: 'books_by_author')]
    // public function booksByAuthor($authorId,BookRepository $rep): Response
    // {
    //     $books = $rep->findBooksByAuthor($authorId);

    //     return $this->render('book/books_by_author.html.twig', [
    //         'books' => $books,
    //     ]);
    // }

    // public function listBook(BookRepository $bookRepository) {
    //     $form=$this-> createForm(SearchBookType::class);
    //     return $this->render('book/listBook.twig');
    
    // }
}
