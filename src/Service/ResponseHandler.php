<?php

namespace App\Service;

use App\Repository\IngredientRepository;
use App\Repository\MealRepository;
use App\Repository\MealTranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class ResponseHandler
{
    public function __construct(
        private MealRepository $mealRepository,
        private MealTranslationRepository $mealTranslationRepository,
        private IngredientRepository $ingredientRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
        private PaginatorInterface $paginator,
        private urlGeneratorInterface $urlGenerator,
        private MealTranslator $translator
    ) {
    }

    public function resolve(array $params): array
    {
        $mealsQuery = $this->mealRepository->getFilteredMealsQuery($params);
        $groups = ['meal'];

        $perPage = $params['per_page'];
        $page = $params['page'];
        $with = $params['with'] ?? null;
        $locale = $params['lang'];

        if($with) {

            if(in_array('category', $with)) {
                array_push($groups, 'meal-category', 'category');
            }

            if (in_array('tags', $with)) {
                array_push($groups, 'meal-tags', 'tag');
            }


            if (in_array('ingredients', $with)) {
                array_push($groups, 'meal-ingredients', 'ingredient');
            }
        }

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups($groups)
            ->toArray();

        $pagination = $this->paginator->paginate($mealsQuery, $page, $perPage);
        $data = $this->serializer->serialize($pagination, 'json', $context);
        $data = json_decode($data);
        $data = $this->translator->translateMealData($data, $locale);

        $meta = [
            "currentPage" => $pagination->getCurrentPageNumber(),
            "totalItems" => $pagination->getTotalItemCount(),
            "itemsPerPage" => $pagination->getItemNumberPerPage(),
            "totalPages" => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
        ];

        $links = [
            "previousPage" => null,
            "nextPage" => null,
            "self" => null
        ];

        $json = [
            'meta' => $meta,
            'data' => $data,
            'links' => $links
        ];


        return $json;
    }
}
