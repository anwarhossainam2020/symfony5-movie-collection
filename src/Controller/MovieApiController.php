<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\ApiController;

class MovieApiController extends ApiController
{
    /**
    * @Route("/api/movie", methods="GET", name="movie_api")
    */
    public function index(MovieRepository $movieRepository)
    {
        $movies = $movieRepository->transformAll();
        return $this->respond($movies);
    }

    /**
    * @Route("/api/movie", methods="POST", name="movie_api_create")
    */
    public function create(Request $request, MovieRepository $movieRepository, EntityManagerInterface $em)
    {
        
       if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        // validate the title
        if (! $request->get('title')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new movie
        $movie = new Movie;
        $movie->setTitle($request->get('title'));
        $movie->setCount(0);
        $em->persist($movie);
        $em->flush();

        return $this->respondCreated($movieRepository->transform($movie));
    }


    /**
    * @Route("/api/movie", methods="PATCH|PUT", name="movie_api_update")
    */
    public function update(Request $request, EntityManagerInterface $em, MovieRepository $movieRepository)
    {

        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }
        if (! $request->get('id')) {
            return $this->respondValidationError('Please provide a ID!');
        }

        // validate the title
        if (! $request->get('title')) {
            return $this->respondValidationError('Please provide a title!');
        }

        // persist the new movie
        $movie = $movieRepository->find($request->get('id'));
        if (! $movie) {
            return $this->respondNotFound();
        }

        $movie->setTitle($request->get('title'));
        $movie->setCount($request->get('count') ? $request->get('count') : $movie->getCount());
        $em->persist($movie);
        $em->flush();

        return $this->respondUpdated($movieRepository->transform($movie));
    }

    /**
    * @Route("/api/movie", methods="DELETE", name="movie_api_destroy")
    */
    public function destroy(Request $request, EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        if (! $request) {
            return $this->respondValidationError('Please provide a valid request!');
        }

        if (! $request->get('id')) {
            return $this->respondValidationError('Please provide a ID!');
        }

        $movie = $movieRepository->find($request->get('id'));
        if (! $movie) {
            return $this->respondNotFound();
        }

        $em->remove($movie);
        $em->flush();
        return $this->respondDeleted(['status' => 'success', 'msg' => 'Movie deleted']);
    }

    /**
    * @Route("/api/movie/{id}/count/increase", methods="POST", name="movie_api_increase_count")
    */
    public function increaseCount($id, EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $movie = $movieRepository->find($id);

        if (! $movie) {
            return $this->respondNotFound();
        }

        $movie->setCount($movie->getCount() + 1);
        $em->persist($movie);
        $em->flush();

        return $this->respond([
            'count' => $movie->getCount()
        ]);
    }

    /**
    * @Route("/api/movie/{id}/count/decrease", methods="POST", name="movie_api_decrease_count")
    */
    public function decreaseCount($id, EntityManagerInterface $em, MovieRepository $movieRepository)
    {
        $movie = $movieRepository->find($id);

        if (! $movie) {
            return $this->respondNotFound();
        }

        $movie->setCount($movie->getCount() == 0 ? 0 : ($movie->getCount() - 1 ));
        $em->persist($movie);
        $em->flush();

        return $this->respond([
            'count' => $movie->getCount()
        ]);
    }


}
