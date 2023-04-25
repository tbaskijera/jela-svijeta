<?php

namespace App\Service;

use App\Repository\IngredientRepository;
use App\Repository\MealRepository;
use App\Repository\MealTranslationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ResponseHandler
{
    public function __construct(
        private MealRepository $mealRepository,
        private MealTranslationRepository $mealTranslationRepository,
        private IngredientRepository $ingredientRepository,
        private SerializerInterface $serializer,
        private EntityManagerInterface $entityManager,
        private TranslatorInterface $translator,
        private PaginatorInterface $paginator,
        private urlGeneratorInterface $urlGenerator,
    ) {
    }

    public function resolve(array $params, Request $request)
    {
        $mealsQuery = $this->mealRepository->getFilteredMealsQuery($params);
        $groups = ['meal'];

        $perPage = $params['per_page'];
        $page = $params['page'];
        $with = $params['with'] ?? null;

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

        foreach ($data as $key => $value) {
            $translation = $this->translator->trans($value->title, [], 'meals', 'es'); // does not work
            // $data[$key] = $translation;
        }

        $meta = [
            "currentPage" => $pagination->getCurrentPageNumber(),
            "totalItems" => $pagination->getTotalItemCount(),
            "itemsPerPage" => $pagination->getItemNumberPerPage(),
            "totalPages" => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage()),
        ];

        $links = [
            "previousPage" => null,
            "nextPage" => null,
        ];

        $json = [
            'meta' => $meta,
            'data' => $data,
            'links' => $links
        ];


        return $json;
    }

}
