<?php

namespace App\CentralLogics;


use App\Model\Category;
use App\Model\Product;
use App\Model\Review;
use App\Model\Wishlist;

class ProductLogic
{
    public static function get_product($id)
    {
        return Product::active()->with(['rating'])->where('id', $id)->first();
    }

    public static function get_latest_products($limit, $offset, $product_type, $name, $category_ids)
    {
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        $key = explode(' ', $name);
        $paginator = Product::active()
            ->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }})
            ->when(isset($product_type) && ($product_type == 'veg' || $product_type == 'meat' || $product_type == 'chicken' || $product_type == 'sea_food'), function ($query) use ($product_type) {
                return $query->productType($product_type);
            })
            ->when(isset($category_ids), function ($query) use ($category_ids) {
                return $query->whereJsonContains('category_ids', ['id'=>$category_ids]);
            })
            ->with(['rating'])
            ->latest()
            ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function get_wishlished_products($limit, $offset, $request)
    {
        $product_ids = Wishlist::where('user_id', $request->user()->id)->get()->pluck('product_id')->toArray();
        $products = Product::active()->with(['rating'])
            ->whereIn('id', $product_ids)
            ->orderBy("created_at", 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $products->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $products->items()
        ];
    }

    public static function get_popular_products($limit, $offset, $product_type)
    {
        $limit = is_null($limit) ? 10 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        $paginator = Product::active()
            ->when(isset($product_type) && ($product_type == 'veg' || $product_type == 'meat' || $product_type == 'chicken' || $product_type == 'sea_food'), function ($query) use ($product_type) {
                return $query->productType($product_type);
            })
            ->with(['rating'])
            ->orderBy('popularity_count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }

    public static function get_related_products($product_id)
    {
        $product = Product::find($product_id);
        return Product::active()->with(['rating'])->where('category_ids', $product->category_ids)
            ->where('id', '!=', $product->id)
            ->limit(10)
            ->get();
    }

    public static function search_products($name, $limit, $offset, $product_type)
    {
        $limit = is_null($limit) ? 25 : $limit;
        $offset = is_null($offset) ? 1 : $offset;

        if($product_type != 'veg' && $product_type != 'meat' && $product_type != 'chicken' && $product_type != 'sea_food') {
            $product_type = 'all';
        }

        $key = explode(' ', $name);
        $paginator = Product::active()
            ->when(isset($product_type) && ($product_type != 'all'), function ($query) use ($product_type) {
                return $query->productType($product_type);
            })
            ->with(['rating'])->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                    $categories = Category::query()->where('name', 'like', "%{$value}%")->pluck('id')->toArray();
                    foreach ($categories as $cat) {
                        $q->orWhere('category_ids', 'like', "%{$cat}%");
                    }
                }
            })->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => $limit,
            'offset' => $offset,
            'products' => $paginator->items()
        ];
    }


    public static function get_product_review($id)
    {
        $reviews = Review::where('product_id', $id)->get();
        return $reviews;
    }

    public static function get_rating($reviews)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                $rating5 += 1;
            }
            if ($review->rating == 4) {
                $rating4 += 1;
            }
            if ($review->rating == 3) {
                $rating3 += 1;
            }
            if ($review->rating == 2) {
                $rating2 += 1;
            }
            if ($review->rating == 1) {
                $rating1 += 1;
            }
        }
        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

}
