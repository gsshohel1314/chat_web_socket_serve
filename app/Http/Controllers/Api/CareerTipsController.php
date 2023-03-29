<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CareerTipsCollection;
use App\Http\Resources\CareerTipsResource;
use App\Interfaces\CareerTipsInterface;
use App\Models\CareerTips;
use Illuminate\Http\Request;

class CareerTipsController extends Controller
{
    protected $careerTips;

    public function __construct(CareerTipsInterface $careerTips)
    {
        $this->careerTips = $careerTips;
    }

    public function index()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = CareerTips::query()
                ->with('careerTips','categories')
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'desc')
                ->paginate($perPage);

            return new CareerTipsCollection($query);
        } else {
            $query = CareerTips::query()->with('careerTips', 'categories')->where('published',1)->paginate(10);

            return new CareerTipsCollection($query);
        }
    }

    public function store(Request $request)
    {
        // return $request->all();
        $data = $request;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'career_tips',
                    'images' => $data->image,
                    'directory' => 'career_tips',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];
        $careerTips = $this->careerTips->create($data, $parameters);
        $careerTipsCategory = $careerTips->categories()->attach($request->categories);

        return new CareerTipsResource($careerTips, $careerTipsCategory);
    }


    public function show(CareerTips $career_tip)
    {
        $careerTips = $this->careerTips->findOrFail($career_tip->id);
        $careerTips['image'] =  $careerTips->careerTips ? $careerTips->careerTips->source : "";
        return response()->json($careerTips);

    }

    public function edit(CareerTips $careerTips)
    {
        //
    }

    public function update(Request $request)
    {
        $data = $request;
        $data['published'] = $request->published == "true" ? 1 : 0;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'career_tips',
                    'images' => $data->image,
                    'directory' => 'career_tips',
                    'input_field' => 'image',
                    'width' => '416',
                    'height' => '277',
                ],
            ],
        ];

        $careerTips = $this->careerTips->update($request->id, $data, $parameters);
        if ($request->categories){
            $cccCategory = $careerTips->categories()->attach($request->categories);
        }
        return response()->json([
            'data' => $careerTips,
            'cccCategory' => $cccCategory,
            'message' => 'Career Tips Updated Successfully',
        ], 200);
    }

    public function destroy($id)
    {
        $this->careerTips->delete($id);

        return response()->json([
            'message' => 'Career Tips deleted Successfully',
        ], 200);
    }
}
