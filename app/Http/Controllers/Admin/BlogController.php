<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('author');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'published') {
                $query->where('is_published', true);
            } elseif ($request->status === 'draft') {
                $query->where('is_published', false);
            }
        }

        $posts = $query->latest()->paginate(20);

        return view('admin.blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.blog.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        // Set author
        $validated['author_id'] = Auth::id();

        // Set published_at if publishing
        if ($validated['is_published'] && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        BlogPost::create($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BlogPost $blog)
    {
        return view('admin.blog.show', compact('blog'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', compact('blog'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        // Set published_at if publishing for the first time
        if ($validated['is_published'] && !$blog->published_at && !$validated['published_at']) {
            $validated['published_at'] = now();
        }

        $blog->update($validated);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BlogPost $blog)
    {
        // Delete featured image
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }

        $blog->delete();

        return redirect()->route('admin.blog.index')
            ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Generate sitemap
     */
    public function generateSitemap()
    {
        $posts = BlogPost::published()->get();

        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Add homepage
        $sitemap .= '<url>' . "\n";
        $sitemap .= '<loc>' . url('/') . '</loc>' . "\n";
        $sitemap .= '<changefreq>daily</changefreq>' . "\n";
        $sitemap .= '<priority>1.0</priority>' . "\n";
        $sitemap .= '</url>' . "\n";

        // Add pricing page
        $sitemap .= '<url>' . "\n";
        $sitemap .= '<loc>' . route('pricing') . '</loc>' . "\n";
        $sitemap .= '<changefreq>weekly</changefreq>' . "\n";
        $sitemap .= '<priority>0.8</priority>' . "\n";
        $sitemap .= '</url>' . "\n";

        // Add blog posts
        foreach ($posts as $post) {
            $sitemap .= '<url>' . "\n";
            $sitemap .= '<loc>' . route('blog.post', $post) . '</loc>' . "\n";
            $sitemap .= '<lastmod>' . $post->updated_at->toAtomString() . '</lastmod>' . "\n";
            $sitemap .= '<changefreq>monthly</changefreq>' . "\n";
            $sitemap .= '<priority>0.6</priority>' . "\n";
            $sitemap .= '</url>' . "\n";
        }

        $sitemap .= '</urlset>';

        // Save sitemap
        Storage::disk('public')->put('sitemap.xml', $sitemap);

        return redirect()->route('admin.blog.index')
            ->with('success', 'Sitemap generated successfully.');
    }
}
