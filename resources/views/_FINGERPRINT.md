# 设计指纹 - codemastersconnect-darknews

## 截图观察要点

- 三张截图均为深色新闻/杂志风格，主背景接近纯黑，内容卡片为深灰，文字以白色和浅灰为主。
- 首页首屏是「上方 logo 条 + 下方导航条 + Hero 主焦点 + 右侧列表」，随后进入「主列表 + 右侧栏」结构。
- 分类页与文章页都使用左主内容、右侧边栏；面包屑位于内容区顶部，深灰底细边框。
- 卡片有轻边框和深色底，标题悬停/激活用红色强调；整体阴影很轻，重点靠色块对比而不是大阴影。
- 文章页正文为深色底、浅灰正文，H2/H3 层级清晰；FAQ 适合用折叠问答样式。

## 配色

- 主色：#d72924（按钮、导航激活线、标题 hover）
- 辅色/强调色：#00c6fc（少量强调链接）、#f2ab05（标签/点缀）
- 背景色：#000000（页面主背景）
- 内容区背景：#111111 / #202020 / #343434（卡片、侧边栏、页脚块）
- 文字色：#d7d7d7（正文）
- 标题色：#ffffff
- 链接色：#ffffff / 链接 hover 色：#d72924
- 分隔线/边框色：#2a2a2a

## 字体

- 标题字体族：'Roboto', serif
- 正文字体族：'Open Sans', sans-serif
- 导航字体族：'Roboto', serif
- 字体来源：Google Fonts
- 正文字号参考：16px
- 行高参考：1.5

## HTML DOM 骨架（写 Blade 前完成；只记结构模式，不抄 WP 类名）

- layout（对照 index.html body 顶层）：`body > #page.site.af-whole-wrapper > (skip-link + header + content sections + footer)`；header 内包含「middle branding 行 + navigation bar 行」，不是单一简化 header。
- 首页 / 分类 / 文章 content 区：  
  - 首页：`hero banner section` 在内容主区前；其后 `#content.container-wrapper > section.section-block-upper > #primary + #secondary`  
  - 分类：`#content.container-wrapper > breadcrumb + #primary(网格列表+分页) + #secondary`  
  - 文章：`#content.container-wrapper > breadcrumb + section.section-block-upper > #primary(文章) + #secondary`

## 模块取舍（对照 source 三页，有则做、无则删——禁止凑齐资讯站标准模块）

- 顶栏/导航：有；双条（logo 条 + 主菜单条），都在 header 内
- Hero / 焦点头条区：有；首页顶部焦点 + 侧列
- 首页文章列表形态：网格卡片
- 首页分类聚合区块：无明显 tab 聚合；采用最近文章主区 + 热门侧栏
- 侧边栏（列表/详情）：有；右侧，内容为热门/推荐列表
- Footer：有；主 footer 多列 + 底部二级 footer

## 交互取舍（仅 source 出现或截图/CSS/JS 可证实的才实现）

- 移动菜单：有；汉堡按钮展开/收起
- 返回顶部：无
- 搜索交互：有图标入口，但本次仅保留视觉入口，不实现搜索覆盖层
- 其他 source 特有交互：首页焦点区（静态首屏实现，未做轮播）

## 类名与资源路径模式（从 source CSS/HTML 提取，禁止自造「主题 slug 隔离体系」）

- source 典型 class/id 模式：`af-` / `aft-` 前缀 + `read-single` / `container-wrapper` 等短语义组合
- 禁止使用的自造模式：`theme-*`、`site-*` 全套隔离前缀
- source CSS 路径样例：`.../themes/darknews-pro/style.css`、`.../assets/bootstrap/css/bootstrap.min.css`
- source JS 路径样例：`.../themes/darknews-pro/js/navigation.js`
- 产出路径镜像组织：`public/assets/css/`、`public/assets/js/`（不用 `themes/{slug}`）

## 版式骨架

### 首页

- 整体布局：Hero 焦点 + 下方双栏（主列表 + 侧栏）
- Header：深色背景，非透明，导航条分层
- Hero 区：有，深色卡片 + 图文
- 文章卡片排列：竖排图上文下
- 各板块顺序：Header → Hero → 主列表/侧栏 → Footer
- Footer：3 列主区 + 底部单行

### 分类列表页

- 布局：侧边栏右
- 文章卡片样式：三列网格深色卡片
- 侧边栏内容：热门文章列表

### 文章详情页

- 主内容区宽度：宽栏（约 70%）
- 侧边栏：右侧
- 正文排版特征：深底浅字、段落留白明显
- 文章头部信息：标题 + 作者/时间/分类元信息
- FAQ 区域：有（可折叠）
- 相关文章区：有（简化卡片）

## 卡片风格

- 圆角：小（4-6px）
- 阴影：浅
- 边框：细线 #2a2a2a
- 图片比例：16:9
- Hover 效果：标题变色、卡片轻微上浮

## 导航风格

- 位置：顶部非透明
- 背景：深色
- Logo 位置：左
- 下拉菜单：可选（本次主做一级）
- 移动端折叠方式：汉堡展开

## 调性关键词

杂志感 / 深色科技 / 资讯站

## 特殊视觉细节

- 导航当前项下方短红线
- 区块标题使用左侧红色竖线/短线强调
- 侧栏卡片有深灰层级区分
- 全局以深灰层级块形成分区，而非纯白留白分区

## 静态资源命名方案

- 标识符：darknews
- 样式文件路径清单（完整路径，逐条对照 source link 标签）：
  - `public/assets/css/style_598f9e85.css` → 全站公共/布局/页面样式（对应 source 主样式 `style.css`）
- 脚本文件路径清单（对照 source script 标签）：
  - `public/assets/js/v833ccba57c9e4d2798f2e76cebdd09a11778172276447.js` → 导航折叠与 FAQ 折叠
  - `public/assets/js/lazyload.min.js` → 预留轻量 lazyload 占位脚本
- CSS 类名风格：`af-/aft-` 站点前缀 + 短语义组合
- Partial 文件命名风格示例：`banner-carousel` / `read-single-card` / `sidebar-widgets`

## 版式变体决策

- Hero 区呈现形式：深色卡片 + 文字（非纯背景图）
- 文章卡片排列方式：竖排图上文下
- 分类页侧边栏位置：右
- 分页方式：数字分页
- 首页分区数量：2 个核心区（Hero + Recent Posts 主区）+ 侧边栏热点

## 自检结果

- [x] _FINGERPRINT.md 已生成，含完整指纹与资源命名方案
- [x] 每个页面有且只有一个 H1
- [x] H 标签层级无跳跃（H1→H2→H3，无 H4+）
- [x] FAQ 区域仅在有数据时渲染，FAQ 区块标题使用 H2，每条问题使用 H3
- [x] 面包屑最后一项无 <a> 标签（不可点击）
- [x] 面包屑字段用的是 $crumb['absolute_url']，不是 $crumb['url']
- [x] 所有 <img> 均有非空 alt 属性
- [x] 文章详情页未渲染 $blog->head_img
- [x] 无任何 penci-* / wp-block-* / magcat-* 类名
- [x] 面包屑 HTML 中无 itemprop / itemscope / itemtype 属性
- [x] 无 javascript:void(0) 链接
- [x] 无 <a> 标签嵌套
- [x] 移动端导航通过 click 而非 hover 触发
- [x] CSS 类名命名体系全文一致
- [x] 资源引用使用 asset() 函数，无硬编码路径
- [x] Blade 注释使用 {{-- --}}，无 HTML 注释
- [x] partials/article-list.blade.php 存在（供 AJAX 调用）
- [x] 分页链接为真实 URL（非 JS 伪链接）
- [x] JSON-LD 覆盖：首页 WebSite、分类 CollectionPage、文章 Article+BreadcrumbList；FAQPage 仅在 $blog->faq 非空时输出
- [x] <html lang> 使用 app()->getLocale()
- [x] $alternate_tag 在 <head> 中输出
- [x] 产出风格与 source 指纹匹配；有截图时已记录截图观察要点
- [x] DOM：指纹含「HTML DOM 骨架」；layout/各页 content 嵌套对齐 source，未默认 header+main+footer（source 实际使用 header+section+footer）
- [x] 类名：延续 source 命名模式，无自造 `{slug}-*`/`theme-*` 全套前缀
- [x] 资源路径：CSS/JS 路径对照 source link/script，未默认 `themes/{slug}/` 目录
- [x] 反模板库：换 source 后模块顺序与交互清单会随指纹变化，非仅换配色
