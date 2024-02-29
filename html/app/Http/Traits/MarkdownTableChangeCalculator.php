<?

namespace App\Http\Traits;

trait MarkdownTableChangeCalculator
{
    /**
     * Extracts and calculates changes in structured data between two texts.
     *
     * @return array An array containing the counts of removed and added structured data items.
     */
    private function calculateTableChanges(): array
    {
        $currentData = $this->extractStructuredData($this->content);
        $updatedData = $this->extractStructuredData($this->updatedContent);

        $removed = 0;
        $added = 0;

        foreach ($currentData as $currentItem) {
            $found = false;
            foreach ($updatedData as $updatedItem) {
                if ($currentItem === $updatedItem) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $removed++;
            }
        }

        foreach ($updatedData as $updatedItem) {
            $found = false;
            foreach ($currentData as $currentItem) {
                if ($currentItem === $updatedItem) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $added++;
            }
        }

        return [
            'removed' => $removed,
            'added' => $added,
        ];
    }

    /**
     * Extracts structured data from a text.
     *
     * @param string $text The text to extract structured data from.
     * @return array An array of structured data extracted from the text.
     */
    private function extractStructuredData(string $text): array
    {
        $data = [];
        preg_match_all('/(\w+): "(.*?)"/', $text, $dataMatches, PREG_SET_ORDER);
        foreach ($dataMatches as $dataMatch) {
            $data[$dataMatch[1]] = $dataMatch[2];
        }

        return $data;
    }
}