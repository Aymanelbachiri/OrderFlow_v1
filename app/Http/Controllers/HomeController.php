<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PricingPlan;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        return redirect()->route('blog');
    }

    public function pricing()
    {
        $pricingPlans = PricingPlan::where('is_active', true)
            ->where('plan_type', 'regular')
            ->orderBy('server_type')
            ->orderBy('device_count')
            ->orderBy('duration_months')
            ->get()
            ->groupBy(['server_type', 'device_count']);

        $genericPlans = PricingPlan::where('is_active', true)
            ->where('plan_type', 'regular')
            ->where('server_type', 'generic')
            ->orderBy('custom_label')
            ->orderBy('device_count')
            ->orderBy('duration_months')
            ->get()
            ->groupBy(['custom_label', 'device_count']);

        return view('pricing', compact('pricingPlans', 'genericPlans'));
    }

    // public function blog()
    // {
    //     $posts = BlogPost::published()
    //         ->with('author')
    //         ->latest('published_at')
    //         ->paginate(10);

    //     return view('blog.index', compact('posts'));
    // }

    // public function blogPost(BlogPost $post)
    // {
    //     if (!$post->is_published) {
    //         abort(404);
    //     }

    //     return view('blog.show', compact('post'));
    // }

    

   
}
