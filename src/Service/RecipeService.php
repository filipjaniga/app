<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
* Class RecipeService.
*/
class RecipeService
{
    /**
    * Recipe repository.
    *
    * @var \App\Repository\RecipeRepository
    */
    private $recipeRepository;

    /**
    * Paginator.
    *
    * @var \Knp\Component\Pager\PaginatorInterface
    */
    private $paginator;

    /**
     * Category service.
     *
     * @var \App\Service\CategoryService
     */
    private $categoryService;



    /**
     * RecipeService constructor.
     *
     * @param \App\Repository\RecipeRepository          $recipeRepository  Recipe repository
     * @param \Knp\Component\Pager\PaginatorInterface $paginator       Paginator
     * @param \App\Service\CategoryService            $categoryService Category service
     */
    public function __construct(RecipeRepository $recipeRepository, PaginatorInterface $paginator, CategoryService $categoryService)
    {
        $this->recipeRepository = $recipeRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
    }

    /**
     * Create paginated list.
     *
     * @param int                                                 $page    Page number
     * @param array                                               $filters Filters array
     *
     * @return \Knp\Component\Pager\Pagination\PaginationInterface Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->recipeRepository->queryAll($filters),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

        /**
         * Save recipe.
         *
         * @param \App\Entity\Recipe $recipe Recipe entity
         *
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

        /**
         * Delete recipe.
         *
         * @param \App\Entity\Recipe $recipe Recipe entity
         *
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }



    /**
     * Prepare filters for the recipes list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category']) && is_numeric($filters['category'])) {
            $category = $this->categoryService->findOneById(
                $filters['category']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }


        return $resultFilters;
    }
}
