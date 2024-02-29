<?

namespace App\Http\Controllers\Articles;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkdownArticleUpdateRequest;
use App\Models\MarkdownArticle;
use Illuminate\Support\Facades\File;
use League\CommonMark\CommonMarkConverter;

class ArticleController extends Controller
{

    public function index()
    {
        $markdownDirectory = public_path('test3/articles');
        $markdownFiles = File::files($markdownDirectory);

        $converter = new CommonMarkConverter();

        $articles = [];

        foreach ($markdownFiles as $file) {

            $article = new MarkdownArticle($file->getPathname());

            $articles[] = [
                'filename' => $article->getFileName(),
                'title' => $article->title,
                'views' => $article->views,
                'category' => $article->category,
                'publishedOn' => date('d M Y, H:i', strtotime($article->publishedOn)),
            ];
        }

        return view('home', compact('articles'));
    }

    public function edit($file) 
    {
        $filePath = public_path('test3/articles/' . $file . '.md');

        if (!file_exists($filePath))
            abort(404);

        $article = new MarkdownArticle($filePath);

        $data = [
            'filename' => $article->getFileName(),
            'title' => $article->title,
            'content' => $article->getRAWContent()
        ];
        return view('edit', compact('data'));
    }

    public function update($file, MarkdownArticleUpdateRequest $request)
    {
        $filePath = public_path('test3/articles/' . $file . '.md');

        if (!file_exists($filePath))
            abort(404);

        $article = new MarkdownArticle($filePath);

        $article->logUpdateMetrics($request->content);

        return redirect()->route('index');
    }
}