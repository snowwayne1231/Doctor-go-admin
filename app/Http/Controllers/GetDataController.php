<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\AuthException;
// use App\Models\Image;

class GetDataController extends BasicController
{
    //
    public function index(Request $request, $models, $wheres = '')
    {
        $result = $this->parseResultByModelWhere($models, $wheres);
        // dd($result);
        return $this->basicJSON($result);
    }

    public function authorization(Request $request, $models, $wheres = '')
    {
        if (empty($request->customer)) {
            throw new AuthException('必須登入');
        }

        $append = 'customer_id='.$request->customer['id'];

        if (empty($wheres) || $wheres == "all") {
            $wheres = $append;
        } else {
            $wheres .= ','.$append;;
        }

        $result = $this->parseResultByModelWhere($models, $wheres);

        return $this->basicJSON($result);
    }

    public function image(Request $request, $id)
    {
        $image = \App\Models\Image::find($id);
        $file = \Storage::disk('public')->get($image->uri);
        return response($file, 200, [
            'Content-Type' => 'image',
        ])->setMaxAge(604800)
        ->setPublic();
    }

    private function parseResultByModelWhere($models, $wheres) {
        $modelArray = explode(',', $models);
        $name = $modelArray[0];
        $ModelName = "App\\Models\\".$name;

        if ($wheres && $wheres != 'all') {
            $whereArray = explode(',', $wheres);
            $Model = new $ModelName;
            
            foreach ($whereArray as $value) {
                $ary = explode('=', $value);
                if (count($ary) == 1) {
                    if (is_numeric($ary[0])) {
                        $column = 'id';
                        $condition = $ary[0];
                        
                    } else {
                        $column = $ary[0];
                        $condition = 1;
                    }
                    
                } else {
                    $column = $ary[0];
                    $condition = $ary[1];
                }
                $Model = $Model->where($column, $condition);

                if (preg_match('/product$/i', $name)) {
                    $Model->increment('viewed', 1);
                }
            }
            
            $result = $Model->get();
            // dd($result->count());
        } else {
            $result = $ModelName::all();
        }

        $result_array = $result->toArray();

        // dd($result);
        foreach ($result as $idx => $res) {
            for ($i = 1; $i < count($modelArray); $i++) {
                $foreignName = $modelArray[$i];
                $extraData = $res->$foreignName->toArray();

                if ($foreignName == 'chapters') {   // delete chapter content
                    $result_array[$idx][$foreignName] = array_map(function($val){
                        unset($val['content']);
                        return $val;
                    }, $extraData);
                    continue;
                }

                $result_array[$idx][$foreignName] = $extraData;
            }
        }

        return $result_array;
    }

    public function psaContent(Request $request, $id) {
        $model = \App\Models\ArticlePsaChapter::find($id);
        $model->setHidden(['created_at']);
        return $this->basicJSON($model->content);
    }

}

