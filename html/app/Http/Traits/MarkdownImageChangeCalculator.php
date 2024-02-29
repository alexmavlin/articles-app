<?

namespace App\Http\Traits;

trait MarkdownImageChangeCalculator
{
    /**
     * Calculates the number of image entries that have been removed during the update.
     * 
     * @return int The number of removed image entries.
     */
    public function calculateRemovedImages(): int
    {
        $oldImages = $this->extractImages($this->content);
        $newImages = $this->extractImages($this->updatedContent);

        $remainedCount = 0;

        foreach ($oldImages as $oldImage) {
            if (in_array($oldImage, $newImages)) {
                $remainedCount++;
            }
        }

        return count($oldImages) - $remainedCount;
    }

    /**
     * Calculates the number of image entries that have been added during the update.
     * 
     * @return int The number of added image entries.
     */
    public function calculateAddedImages(): int
    {
        $oldImages = $this->extractImages($this->content);
        $newImages = $this->extractImages($this->updatedContent);

        $addedCount = 0;

        foreach ($newImages as $newImage) {
            if (!in_array($newImage, $oldImages)) {
                $addedCount++;
            }
        }

        return $addedCount;
    }

    /**
     * Extracts image entries from the given text.
     * 
     * @param string $text The text to extract image entries from.
     * @return array An array containing image entries (URLs and alt texts).
     */
    private function extractImages(string $text): array
    {
        preg_match_all('/(?:!\[([^\]]*)\]\(|\[([^\]]*)\]\()([^)]+\.(?:gif|jpg|jpeg|webp))/', $text, $matches, PREG_SET_ORDER);

        $images = [];
        foreach ($matches as $match) {
            $altText = !empty($match[1]) ? $match[1] : $match[2];
            $images[] = [$altText, $match[3]]; // Alt text and URL
        }

        return $images;
    }
}