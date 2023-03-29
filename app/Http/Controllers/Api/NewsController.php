<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NewsRequest;
use App\Http\Resources\NewsCollection;
use App\Interfaces\NewsInterface;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    protected $news;

    public function __construct(NewsInterface $news)
    {
        $this->news=$news;
    }

    public function index()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = News::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new NewsCollection($query);
        }else{
            $news = News::query()->where('status','Active')->paginate(10);
            return new NewsCollection($news);
        }
    }

    public function deletedListIndex()
    {
        $news = $this->news->onlyTrashed();

        return response()->json($news);
    }


    public function store(NewsRequest $request)
    {
        /*$news = $this->news->create($request);
        return response()->json(['data' => $news, 'message' => trans('news.created'),], 200);*/

        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'news',
                    'images' => $data->image,
                    'directory' => 'news',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];
        $news = $this->news->create($data, $parameters);
        $cccCategory = $news->categories()->attach($request->categories);

        return response()->json([
            'data' => $news,
            'cccCategory' => $cccCategory,
            'message' => 'News Created Successfully',
            ], 200);
    }

    public function show(News $news)
    {
        $news = $this->news->findOrFail($news->id);
        $news['image'] =  $news->news->source;
        return response()->json($news);
    }

    public function edit($id)
    {
        $news = $this->news->findOrFail($id);

        return response()->json($news);
    }

    public function update(NewsRequest $request, News $news)
    {
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'news',
                    'images' => $data->image,
                    'directory' => 'news',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];

        $news = $this->news->update($news->id,$data,$parameters);
        if ($request->categories){
            $cccCategory = $news->categories()->attach($request->categories);
//            $cccCategory = $news->subCategories()->attach($request->categories);
        }

        $request['update'] = 'update';

        return response()->json([
            'data' => $news,
            'cccCategory' => $cccCategory,
            'message' => trans('news.updated'),
        ], 200);
    }

    public function destroy(News $news)
    {
        $this->news->delete($news->id);

        return response()->json([
            'message' => trans('news.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->news->restore($id);

        return response()->json([
            'message' => trans('news.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->news->forceDelete($id);

        return response()->json([
            'message' => trans('news.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->news->status($request->id);

        return response()->json([
            'message' => trans('news.status_updated'),
        ], 200);
    }
}
