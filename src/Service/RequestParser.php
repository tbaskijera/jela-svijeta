<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class RequestParser
{
    public function normalizeInput(Request $request): array
    {
        $params = [
            'per_page' => $request->query->getInt('per_page', 10),
            'page' => $request->query->getInt('page', 1),
            'category' => $request->query->get('category', null),
            'tags' => $request->query->get('tags', null),
            'with' => $request->query->get('with', null),
            'lang' => $request->query->get('lang', 'en'),
            'diff_time' => $request->query->getInt('diff_time', 0)
        ];

        $params['category'] = $this->normalizeCategory($params['category']);
        $params['tags'] = $this->normalizeTags($params['tags']);
        $params['with'] = $this->normalizeWith($params['with']);

        $params = array_filter($params, function ($value) {
            return $value !== null && $value !== 0;
        });

        return $params;
    }

    private function normalizeCategory($category)
    {
        if (ctype_digit((string) $category)) {
            return (int) $category;
        } else {
            return $category;
        }
    }

    private function normalizeTags($tags)
    {
        if ($tags !== null) {
            return array_map('intval', explode(',', $tags));
        } else {
            return $tags;
        }
    }

    private function normalizeWith($with)
    {
        if($with !== null) {
            return explode(',', $with);
        } else {
            return $with;
        }
    }
}
