<?

namespace App\Models;

use App\Http\Interfaces\ArticleInterface;
use App\Http\Traits\MarkdownArticleTrait;
use App\Http\Traits\MarkdownImageChangeCalculator;
use App\Http\Traits\MarkdownTableChangeCalculator;
use App\Http\Traits\MarkdownWordChangeCalculator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class MarkdownArticle extends Article
{
    use MarkdownArticleTrait,
        MarkdownWordChangeCalculator,
        MarkdownImageChangeCalculator,
        MarkdownTableChangeCalculator;

    public function __construct($path)
    {
        $this->path = $path;
        $this->content = File::get($path);;
        $this->title = $this->getMeta('title');
        $this->views = $this->getMeta('views');
        $this->category = $this->getMeta('category');
        $this->metaDescription = $this->getMeta('meta_description');
        $this->publishedOn = $this->getMeta('published_on');
        $this->keywords = $this->getMeta('keywords');
    }

    /**
     * Logs content changes
     * 
     * @param string $newState Updated article content.
     */
    public function logUpdateMetrics(string $newState): void
    {
        $metrics = $this->getUpdateMetrics($newState);

        foreach ($metrics as $key => $metric)
        {
            Log::channel('audit')->info($key . ': ' . $metric);
        }
    }

    /**
     * Gets the Update metrics for words, images, and tables.
     * 
     * @param string $newState Updated article content.
     * @return array Array of metrics.
     */
    private function getUpdateMetrics(string $newState): array 
    {
        $this->setUpdatedContent($newState);
        return [
            'words' => $this->getWordDifferenceOnUpdate(),
            'added_images' => $this->getAddedImages(),
            'removed_images' => $this->getRemovedImages(),
            'tables' => $this->getTableChangeMetrics(),
        ];
    }

    /**
     * Gets the count of removed and added words during the update of the file content.
     * 
     * @return string Removed/Added words metrics.
     */
    private function getWordDifferenceOnUpdate(): string
    {
        $this->currentWordArray = preg_split('/\s+/', $this->removePunctuation($this->content));
        $this->updatedWordArray = preg_split('/\s+/', $this->removePunctuation($this->updatedContent));

        $removedWordsCount = $this->calculateRemovedWordsOnUpdate();
        $addedWordsCount = $this->calculateAddedWordsOnUpdate();
        return $removedWordsCount . ' word(s) removed, ' . $addedWordsCount . ' word(s) added.';
    }

    /**
     * Gets the count of removed images from the text.
     * 
     * @return string Removed images metrics.
     */
    private function getRemovedImages(): string 
    {
        $removedImagesCount = $this->calculateRemovedImages();
        return $removedImagesCount . ' image(s) removed.';
    }

    /**
     * Gets the count of added images from the text.
     * 
     * @return string Added images metrics.
     */
    private function getAddedImages(): string 
    {
        $addedImagesCount = $this->calculateAddedImages();
        return $addedImagesCount . ' image(s) added.';
    }

    /**
     * Gets the count of removed and added tables.
     * 
     * @return string Added/Removed tables metrics.
     */
    private function getTableChangeMetrics(): string 
    {
        $numMetrics = $this->calculateTableChanges();
        return $numMetrics['removed'] . ' table(s) removed, ' . 
                $numMetrics['added'] . ' table(s) added.';
    }
}