<?php
namespace App\Http\Traits;

trait MarkdownWordChangeCalculator
{

    /**
     * Calculates words that have been removed during the update.
     * 
     * @return int Quantity of removed words
     */
    private function calculateRemovedWordsOnUpdate(): int
    {
        $currentValueCount = array_count_values($this->currentWordArray);
        $updatedValueCount = array_count_values($this->updatedWordArray);
    
        $removedCount = 0;
    
        foreach ($currentValueCount as $word => $count) {
            if (!isset($updatedValueCount[$word]) || $updatedValueCount[$word] < $count) {
                $removedCount += $count - ($updatedValueCount[$word] ?? 0);
            }
        }
    
        return $removedCount;
    }
    
    /**
     * Calculates words that have been added during the update.
     * 
     * @return int Quantity of added words.
     */
    private function calculateAddedWordsOnUpdate(): int
    {
        $currentValueCount = array_count_values($this->currentWordArray);
        $updatedValueCount = array_count_values($this->updatedWordArray);
    
        $addedCount = 0;
    
        foreach ($updatedValueCount as $word => $count) {
            if (!isset($currentValueCount[$word]) || $currentValueCount[$word] < $count) {
                $addedCount += $count - ($currentValueCount[$word] ?? 0);
            }
        }
    
        return $addedCount;
    }


    /**
     * Removes all the unneccesary punctuation for word calculations.
     * 
     * @param string $text Text to be cleaned.
     * @return string Clean text without punctuation.
     */
    private function removePunctuation($text): string
    {
        $charactersToRemove = ['-', ':', '"', '?', '!', '[', ']', ',', '\\', '/'];

        return str_replace($charactersToRemove, '', $text);
    }
}