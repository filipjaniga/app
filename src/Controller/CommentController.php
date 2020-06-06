<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\CommentRepository        $commentRepositoryComment repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="comment_index",
     * )
     */
    public function index(Request $request,CommentRepository $commentRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $commentRepository->queryAll(),
            $request->query->getInt('page', 1),
           CommentRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'comment/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP request
     * @param \App\Repository\CommentRepository        $commentRepository Comment repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="comment_create",
     * )
     */
    public function create(Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new \DateTime());
            $comment->setUpdatedAt(new \DateTime());
            $commentRepository->save($comment);

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'comment/create.html.twig',
            ['form' => $form->createView()]
        );
    }

}