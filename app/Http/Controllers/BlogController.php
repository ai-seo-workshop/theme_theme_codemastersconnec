<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\MaterielTask;

class BlogController extends Controller
{
    /**
     * 首页
     */
    public function index()
    {
        $seoInfo     = $this->seoInfo(MaterielTask::TYPE_HOME);
        $categoryIds = $this->categories->pluck('id')->toArray();


        // 1. 获取SEO信息
        $seoInfo = $this->seoInfo(MaterielTask::TYPE_HOME);

        $categoryIds = $this->categories->pluck('id')->toArray();
        $categoryInfo = MaterielTask::byLanguage($this->locale)->where('type', MaterielTask::TYPE_CATEGORY)->get();

        // 热门文章
        $hotBlogs = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->select('id', 'title', 'title_uniq', 'head_img', 'head_img_alt', 'summary', 'category_id', 'volume', 'category_name', 'published_at', 'language')
            ->orderBy('volume', 'desc')
            ->limit(4) // 限制查询总数
            ->get();

        // 最新文章：取前5
        $latestBlogs = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->select('id', 'title', 'title_uniq', 'head_img', 'head_img_alt', 'summary', 'category_id', 'volume', 'category_name', 'published_at', 'language')
            ->orderBy('published_at', 'desc')
            ->limit(6) // 限制查询总数
            ->get();

        $blogsFlat = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->whereRaw('(
                SELECT COUNT(*)
                FROM google_blogs as b2
                WHERE b2.category_id = google_blogs.category_id
                AND b2.published_at >= google_blogs.published_at
                AND b2.state = 1
            ) <= ?', [5])
            ->select('id', 'title', 'title_uniq', 'head_img', 'head_img_alt', 'summary', 'category_id', 'volume', 'category_name', 'published_at', 'language')
            ->orderBy('category_id', 'asc')
            ->orderBy('published_at', 'desc')
            ->get();
        $blogs = $blogsFlat->groupBy('category_id');

        $hotPosts = Blog::active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->limit(4)
            ->get();

        $latestPosts = Blog::active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->limit(6)
            ->get();

        return view('home', [
            'crumbs' => $this->crumbs(null, null),
            'alternate_tag' => $this->alternate_tag,
            'gtag' => $this->site->gtag,
            'seoInfo' => $seoInfo,
            'categoryInfo' => $categoryInfo,
            'categories' => $this->categories,
            'hotBlogs' => $hotBlogs,
            'latestBlogs' => $latestBlogs,
            'blogs' => $blogs,
        ]);
    }

    /**
     * 分类列表页
     */
    public function category($locale = 'en', $category = null)
    {
        if ($category === null) {
            $category     = $locale;
            $this->locale = app()->getLocale();
        }

        $categoryInfo = $this->categories->where('slug', $category)->first();
        if (!$categoryInfo) {
            return response()->view('404', ['categories' => $this->categories, 'slogan' => $this->seoInfo(MaterielTask::TYPE_HOME)], 404);
        }
        $seoInfo = $this->seoInfo(MaterielTask::TYPE_CATEGORY, $categoryInfo->id);

        $hotBlogs = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->byCategory($categoryInfo->id)
            ->select('id', 'title', 'title_uniq', 'head_img', 'head_img_alt', 'summary', 'category_id', 'volume',
                'category_name', 'published_at', 'language', 'author')
            ->orderBy('volume', 'desc')
            ->limit(4) // 限制查询总数
            ->get();

        $blogs = Blog::active()
            ->byLanguage($this->locale)
            ->where('category_id', $categoryInfo->id)
            ->latest('published_at')
            ->paginate(12);

        if (request()->ajax()) {
            return response()->json([
                'html' => view('partials.article-list', compact('blogs'))->render(),
                'pagination' => [
                    'current_page'   => $blogs->currentPage(),
                    'last_page'      => $blogs->lastPage(),
                    'has_more_pages' => $blogs->hasMorePages(),
                    'on_first_page'  => $blogs->onFirstPage(),
                ]
            ]);
        }

        return view('category', [
            'crumbs'       => $this->crumbs($categoryInfo, null),
            'gtag'         => $this->site->gtag,
            'categoryInfo' => $categoryInfo,
            'seoInfo'      => $seoInfo,
            'categories'   => $this->categories,
            'slogan'       => $this->seoInfo(MaterielTask::TYPE_HOME),
            'blogs'        => $blogs,
            'hotBlogs' => $hotBlogs,
        ]);
    }

    /**
     * 文章详情页
     */
    public function show($locale = 'en', $title_uniq = null)
    {
        if ($title_uniq === null) {
            $title_uniq   = $locale;
            $this->locale = app()->getLocale();
        }

        $blog = Blog::active()
            ->byLanguage($this->locale)
            ->where('title_uniq', $title_uniq)
            ->first();
        if (!$blog) {
            return response()->view('404', ['categories' => $this->categories, 'slogan' => $this->seoInfo(MaterielTask::TYPE_HOME)], 404);
        }

        $categoryIds = $this->categories->pluck('id')->toArray();

        $popularPosts = Blog::active()
            ->byLanguage($this->locale)
            ->whereIn('category_id', $categoryIds)
            ->latest('published_at')
            ->limit(5)
            ->get();

        // 获取热门文章（侧边栏）
        $popularBlogs = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->where('id', '!=', $blog->id)
            ->orderBy('volume', 'desc')
            ->limit(4)
            ->get();

        // 获取相关文章（同分类）
        $relatedBlogs = Blog::with('category')
            ->active()
            ->byLanguage($this->locale)
            ->byCategory($blog->category_id)
            ->where('id', '!=', $blog->id)
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        return view('blog-detail', [
            'crumbs'       => $this->crumbs($blog->category, $blog),
            'gtag'         => $this->site->gtag,
            'blog'         => $blog,
            'categories'   => $this->categories,
            'slogan'       => $this->seoInfo(MaterielTask::TYPE_HOME),
            'popularBlogs'=>$popularBlogs,
            'relatedBlogs'=>$relatedBlogs,
        ]);


    }
}

