<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ClubRequest;
use App\Http\Resources\ClubCollection;
use App\Interfaces\ClubInterface;
use App\Models\Club;
use Illuminate\Http\Request;

class ClubController extends Controller
{
    protected $club;

    public function __construct(ClubInterface $club)
    {
        $this->club=$club;
    }

    public function index()
    {
        if (request()->per_page) {
            $perPage = request()->per_page;
            $fieldName = request()->field_name;
            $keyword = request()->keyword;

            $query = Club::query()
                ->where($fieldName, 'LIKE', "%$keyword%")
                ->orderBy('id', 'asc')
                ->paginate($perPage);

            return new ClubCollection($query);
        }else{
            $club = $this->club->get();
            return new ClubCollection($club);
        }
    }

    public function deletedListIndex()
    {
        $club = $this->club->onlyTrashed();

        return response()->json($club);
    }


    public function store(ClubRequest $request)
    {
        /*$club = $this->club->create($request);
        return response()->json(['data' => $club, 'message' => trans('club.created'),], 200);*/

        $data = $request;
        $socialLinks = $request->social_link_inputs;
        $parameters = [
            'image_info' => [
                [
                    'type' => 'club_main_logo',
                    'images' => $data->main_logo,
                    'directory' => 'club',
                    'input_field' => 'main_logo',
                    'width' => '483',
                    'height' => '344',
                ],
                [
                    'type' => 'club_header_logo',
                    'images' => $data->header_logo,
                    'directory' => 'club',
                    'input_field' => 'header_logo',
                    'width' => '315',
                    'height' => '65',
                ],
            ],
            'create_many' => [
                [
                    'relation' => 'linkDetails',
                    'data' => $socialLinks
                ],
            ],
        ];
        $club = $this->club->create($data, $parameters);

        return response()->json([
            'data' => $club,
            'message' => 'Club Created Successfully',
        ], 200);
    }

    public function show(Club $club)
    {
        $club = $this->club->with(['linkDetails','clubMedias','clubModerators','clubCommittees'])->findOrFail($club->id);
        /*$club['deletedListMedias'] = $club->clubMedias()->onlyTrashed()->get();
        $club['deletedListModerators'] = $club->clubModerators()->onlyTrashed()->get();
        $club['deletedListCommittees'] = $club->clubCommittees()->onlyTrashed()->get();*/
        $club['main_logo'] =  $club->clubMainLogo->source;
        $club['header_logo'] =  $club->clubHeaderLogo->source;
        return response()->json($club);
    }

    public function edit($id)
    {
        $club = $this->club->with(['clubMainLogo','clubHeaderLogo','linkDetails'])->findOrFail($id);

        return response()->json($club);
    }

    public function update(ClubRequest $request, Club $club)
    {
        $data = $request;
        $update_parameters = [
            'create_many' => [
                [
                    'relation' => 'linkDetails',
                    'data' => $data->social_link_inputs
                ],
            ],
            'image_info' => [
                [
                    'type' => 'club_main_logo',
                    'images' => $data->main_logo,
                    'directory' => 'club',
                    'input_field' => 'main_logo',
                    'width' => '483',
                    'height' => '344',
                ],
                [
                    'type' => 'club_header_logo',
                    'images' => $data->header_logo,
                    'directory' => 'club',
                    'input_field' => 'header_logo',
                    'width' => '315',
                    'height' => '65',
                ],
            ],

        ];

        $club = $this->club->update($club->id, $data, $update_parameters);
        $request['update'] = 'update';

        return response()->json([
            'data' => $club,
            'message' => trans('club.updated'),
        ], 200);
    }

    public function destroy(Club $club)
    {
        $this->club->delete($club->id);

        return response()->json([
            'message' => trans('club.deleted'),
        ], 200);
    }

    public function restore($id)
    {
        $this->club->restore($id);

        return response()->json([
            'message' => trans('club.restored'),
        ], 200);
    }

    public function forceDelete($id)
    {
        $this->club->forceDelete($id);

        return response()->json([
            'message' => trans('club.permanent_deleted'),
        ], 200);
    }

    public function status(Request $request)
    {
        $this->club->status($request->id);

        return response()->json([
            'message' => trans('club.status_updated'),
        ], 200);
    }
}
