<?

namespace App\Models;

use App\Http\Interfaces\ArticleInterface;
use App\Http\Traits\ArticleTableChangeCalculator;
use App\Http\Traits\ArticleTrait;
use App\Http\Traits\ImageChangeCalculator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Article implements ArticleInterface
{
    public $title;
    public $views;
    public $category;
    public $metaDescription;
    public $publishedOn;
    public $keywords;

    protected $path;
    protected $content;
    protected $updatedContent;
    protected $currentWordArray;
    protected $updatedWordArray;

    public function __construct(string $path)
    {
        $this->path = $path;
        $this->content = File::get($path);;
    }

    /**
     * @return string File path name
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets the updated content to the object.
     * 
     * @param string $newState Updated Article content
     */
    public function setUpdatedContent(string $newState): void 
    {
        $this->updatedContent = $newState;
    }

    /**
     * @return string File name without extension.
     */
    public function getFileName() : string
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

    /**
     * @return string Content without conversion
     */
    public function getRAWContent(): string
    {
        return $this->content;
    }

    /**
     * Logs content changes
     * 
     * @param string $newState Updated article content.
     */
    public function logUpdateMetrics(string $newState): void
    {
        
    }
}