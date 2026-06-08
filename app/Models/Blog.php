<?php

namespace App\Models;

use Illuminate\Support\Str;
use QL\QueryList;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table = 'google_blogs';

    protected $fillable = [
        'title', 'title_uniq', 'h1', 'summary', 'content', 'content_faq',
        'head_img', 'keyword', 'language', 'published_at', 'category_id',
        'category_name', 'volume', 'author', 'state'
    ];

    protected $dates = ['published_at', 'create_time', 'update_time'];

    public $timestamps = false;

    // 关联分类
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // 查询作用域 - 只获取可用的博客
    public function scopeActive($query)
    {
        return $query->where('state', 1);
    }

    // 查询作用域 - 按语言过滤
    public function scopeByLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    // 查询作用域 - 按分类过滤
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeGetPerCategory($query, array $categoryIds, $limit = 6)
    {
        return $query->whereIn('category_id', $categoryIds)
            ->orderBy('published_at', 'desc')
            ->get()
            ->groupBy('category_id')
            ->map(function($items) use ($limit) {
                return $items->take($limit);
            })
            ->flatten();
    }

    // 增加阅读量
    public function incrementVolume()
    {
        $this->increment('volume');
    }

    // 获取URL
    public function getUrlAttribute()
    {
        $locale = $this->language;
        if ($locale === 'en') {
            return '/blogs/' . $this->title_uniq . '/';
//            return route_slash('blog.show', ['title_uniq' => $this->title_uniq]);
        }
        return '/'.$locale.'/' . 'blogs/' . $this->title_uniq . '/';
//        return route_slash('blog.show.localized', ['locale' => $locale, 'title_uniq' => $this->title_uniq]);
    }

    public function absoluteUrl()
    {
        $locale = $this->language;
        if ($locale === 'en') {
            return route_slash('blog.show', ['title_uniq' => $this->title_uniq]);
        }
        return route_slash('blog.show.localized', ['locale' => $locale, 'title_uniq' => $this->title_uniq]);
    }

    public function getFaqAttribute()
    {
        $faqData = [];
        $queryList = QueryList::html($this->content_faq);
        if($this->content_faq) {
            $html = $this->content_faq;

            $pattern = '/<h3>(.*?)<\/h3>(.*?)(?=<h3>|$)/s';

            preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

            $faqData = [];
            foreach ($matches as $match) {
                $answers = '';
                // 提取h3内的问题文本
                $question = trim($match[1]);
                // 提取h3后直到下一个h3/结尾的所有内容（包含p/ul）
                $content = trim($match[2]);

                // 提取p标签内的纯文本
                if (preg_match_all('/<p>(.*?)<\/p>/s', $content, $pMatches)) {
                    $answers .= implode('', array_map('trim', $pMatches[1]));
                }
                // 补充提取ul标签内的纯文本（新增：适配ul标签）
                if (preg_match_all('/<ul>(.*?)<\/ul>/s', $content, $ulMatches)) {
                    $answers = $content;
//                    // 进一步提取li标签内容（如果需要保留li文本）
//                    foreach ($ulMatches[1] as $ulContent) {
//                        if (preg_match_all('/<li>(.*?)<\/li>/s', $ulContent, $liMatches)) {
//                            $answers .= implode(' ', array_map('trim', $liMatches[1]));
//                        }
//                    }
                }
                // 兜底：如果没有p/ul，直接用原始内容（去标签）
                if (empty($answers)) {
                    $answers = trim(strip_tags($content));
                }

                $faqData[] = [
                    'question' => $question,
                    'answer' => $answers,
                ];
            }
        }
        return $faqData;
    }

    public function getContentAttribute() {
        $value = $this->attributes['content'] ?? '';

        if ($value) {
            if (Str::contains($value, 'nanjing')) {
                return str_replace('https://cc-1251174242.cos.ap-nanjing.myqcloud.com', '/image', $value);
            } elseif (Str::contains($value, 'hongkong')) {
                return str_replace('https://global-ec-1251174242.cos.ap-hongkong.myqcloud.com', '/img', $value);
            }

        }

        return $value;
    }

    public function getHeadImgAttribute() {
        $value = $this->attributes['head_img'] ?? null;

        if ($value) {
            if (Str::contains($value, 'nanjing')) {
                return str_replace('https://cc-1251174242.cos.ap-nanjing.myqcloud.com', '/image', $value);
            } elseif (Str::contains($value, 'hongkong')) {
                return str_replace('https://global-ec-1251174242.cos.ap-hongkong.myqcloud.com', '/img', $value);
            }
        }

        return $value;
    }

}
