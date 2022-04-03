<?php

namespace App\Service;

class ArticleWordsFilter
{
    /**
     * @param string $string
     * @param array $stopWords
     *
     * @return string
     */
    public function filter(string $string, array $stopWords): string
    {
        $words = explode(' ', $string);

        $wordsToExclude = [];

        foreach ($words as $key => $word) {
            foreach ($stopWords as $stopWord) {
                if (false === mb_stripos($word, $stopWord)) {
                    continue;
                }

                $wordsToExclude[$key] = $word;
            }
        }

        $cleanWordList = array_filter($words, function ($word) use ($wordsToExclude) {
            return false === in_array($word, $wordsToExclude);
        });

        return implode(' ', $cleanWordList);
    }
}
