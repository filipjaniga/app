<?php
/**
 * Category controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
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
   /*
    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     * @param \App\Repository\RecipeRepository $recipeRepository Recipe repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/",
     *     methods={"GET"},
     *     name="category_index",
     * )

    public function index(Request $request, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $categoryRepository->queryAll(),
            $request->query->getInt('page', 1),
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'category/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Category $category Category entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="category_show",
     *     requirements={"id": "[1-9]\d*"},
     * )

    public function show(Category $category): Response
    {
        return $this->render(
            'category/show.html.twig',
            ['category' => $category]
        );
    }
*/

    /**
     * Create comment.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP request
     * @param \App\Repository\CommentRepository $commentRepository Comment repository
     * @param \App\Repository\RecipeRepository $recipeRepository Recipe repository
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET", "POST"},
     *     name="comment_create",
     * )
     */
    public function create(Request $request, CommentRepository $commentRepository, RecipeRepository $recipeRepository, $id): Response
    {
        $comment = new Comment();
        $form = $this->createForm(RecipeType::class, $comment);
        $form->handleRequest($request);

        $recipe = $recipeRepository->find($id);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setRecipe($recipe);
            $commentRepository->save($comment);
            $this->addFlash('success','message_comment_added_successfully');

            return $this->redirectToRoute('recipe_show', ['id' => $comment->getRecipe()->getId()]);
        }

        return $this->render(
            'recipe/show.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
                'recipe' => $recipe,
            ]
        );

    }

}