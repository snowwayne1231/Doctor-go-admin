<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\AuthException;
use App\Models\CustomerWatchlist;
use App\Models\WatchlistArticle;

class WatchlistController extends BasicController
{
    //
    public function index(Request $request, $models, $wheres = '') {
        
    }

    public function addProduct(Request $request) {

        $inputs = $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $customer_id = $request->customer['id'];
        $product_id = intval($inputs['id']);

        if (CustomerWatchlist::where('customer_id', $customer_id)->where('product_id', $product_id)->count() > 0) {
            throw new \Exception('已經存在紀錄');
        }
        
        $newFavorite = new CustomerWatchlist();
        $newFavorite->customer_id = $customer_id;
        $newFavorite->product_id = $product_id;
        $newFavorite->save();

        return $this->basicJSON($newFavorite);
    }

    public function deleteProduct(Request $request) {
        $inputs = $this->validate($request, [
            'id' => 'required',
        ]);

        $customer_id = $request->customer['id'];

        if (is_array($inputs['id'])) {

            $result = CustomerWatchlist::where('customer_id', $customer_id)->whereIn('product_id', $inputs['id'])->delete();

        } else {
            $product_id = intval($inputs['id']);
        
            $result = CustomerWatchlist::where('customer_id', $customer_id)->where('product_id', $product_id)->delete();
        }

        return $this->basicJSON($result);

    }


    public function addArticle(Request $request) {

        $inputs = $this->validate($request, [
            'id' => 'required|numeric',
        ]);

        $customer_id = $request->customer['id'];
        $article_id = intval($inputs['id']);

        if (WatchlistArticle::where('customer_id', $customer_id)->where('article_id', $article_id)->count() > 0) {
            throw new \Exception('已經存在紀錄');
        }
        
        $newFavorite = new WatchlistArticle();
        $newFavorite->customer_id = $customer_id;
        $newFavorite->article_id = $article_id;
        $newFavorite->save();

        return $this->basicJSON($newFavorite);
    }

    public function deleteArticle(Request $request) {
        $inputs = $this->validate($request, [
            'id' => 'required',
        ]);

        $customer_id = $request->customer['id'];

        if (is_array($inputs['id'])) {

            $result = WatchlistArticle::where('customer_id', $customer_id)->whereIn('article_id', $inputs['id'])->delete();

        } else {
            $article_id = intval($inputs['id']);
        
            $result = WatchlistArticle::where('customer_id', $customer_id)->where('article_id', $article_id)->delete();
        }

        return $this->basicJSON($result);

    }

}

