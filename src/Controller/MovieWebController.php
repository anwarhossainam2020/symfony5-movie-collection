<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;
use App\Repository\MovieRepository;

use App\Form\Type\MovieFormType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class MovieWebController extends AbstractController
{
    /**
     * @Route("/", name="movie_web")
     */
    public function index(MovieRepository $movieRepository)
    {
        $movies = $movieRepository->transformAll();
        return $this->render('movie/index.html.twig', [
            'controller_name' => 'MovieWebController',
            'movies' => $movies
        ]);
    }

    /**
     * @Route("/web/movie/new", name="movie_web_new")
     */
    public function create(Request $request, MovieRepository $movieRepository, EntityManagerInterface $entityManager)
    {
        
        $form = $this->createFormBuilder()
            ->add('title', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'form-controll']
            ])
            ->add('count', NumberType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // $form->getData() holds the submitted values

                $movie = new Movie();
                $movie->setTitle($form->get('title')->getData());
                $movie->setCount($form->get('count')->getData());

                $entityManager->persist($movie);
                $entityManager->flush();
       
                return $this->redirectToRoute('movie_web');
            }

        return $this->render('movie/new.html.twig', [
            'controller_name' => 'MovieWebController',
            'form' => $form->createView()            
        ]);
    }

    /**
     * @Route("/web/movie/edit/{id}", name="movie_web_edit")
     */
    public function edit($id, Request $request,EntityManagerInterface $em, MovieFormType $movie, MovieRepository $movieRepository)
    {
        
        $movie = $movieRepository->find($id);
        $form = $this->createForm(MovieFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {            
            $data = $form->getData();
            $movie->setTitle($data['title']);
            $movie->setCount($data['count']);

            $em->persist($movie);
            $em->flush();
            $this->addFlash('success', 'Movie succesfully saved');
            return $this->redirectToRoute('movie_web');
        } else {
            $form->setData($movieRepository->transform($movie));
        }
        return $this->render('movie/edit.html.twig', [
            'controller_name' => 'MovieWebController',
            'form' => $form->createView()            
        ]);
    }

    /**
     * @Route("/web/movie/delete/{id}", name="movie_web_delete")
     */
    public function destroy($id, Request $request, EntityManagerInterface $em, MovieRepository $movieRepository)
    {

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (! $request->get('id')) {
            return $this->respondValidationError('Please provide a ID!');
        }

        $movie = $movieRepository->find($request->get('id'));
        if (! $movie) {
            return $this->redirectToRoute('movie_web');
        }

        $em->remove($movie);
        $em->flush();
        return $this->redirectToRoute('movie_web');  
    }    
}
