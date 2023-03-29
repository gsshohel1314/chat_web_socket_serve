<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\NoticeEventInterface;
use App\Http\Resources\NoticeEventCollection;
use App\Http\Requests\Admin\NoticeEventRequest;
use App\Models\NoticeEvent;


class NoticeEventController extends Controller
{
    protected $noticeevent;

    public function __construct(NoticeEventInterface $noticeevent){
        $this->noticeevent = $noticeevent;
    }
    public function index()
    {
        $data = NoticeEvent::query()->where('status','Active')->select(['id','title','description','time','date','status'])->paginate(6);
        return response()->json($data);
    }

    public function paginatedlist()
    {
        $perPage = request()->per_page;
        $fieldName = request()->field_name;
        $keyword = request()->keyword;

        $query = NoticeEvent::query()
            ->where($fieldName, 'LIKE', "%$keyword%")
            ->orderBy('id', 'asc')
            ->paginate($perPage);

        return new NoticeEventCollection($query);
    }

    public function singlepage()
    {
        $data = NoticeEvent::first();
        return response()->json($data);
    }


    public function create()
    {
        //
    }


    public function store(NoticeEventRequest $request)
    {
        $noticeevent = $this->noticeevent->create($request);
        return response()->json($noticeevent);
    }


    public function show($id)
    {
        $noticeEvent = NoticeEvent::query()->findOrFail($id);

        return response()->json($noticeEvent);
    }

 
    public function edit($id)
    {
        //
    }


    public function update(Request $request, NoticeEvent $notice_event)
    {
        $noticeevent = $this->noticeevent->update($notice_event->id,$request);
        return response()->json($noticeevent);
    }


    public function destroy(NoticeEvent $notice_event)
    {
        $noticeevent = $this->noticeevent->delete($notice_event->id);
        return response()->json($noticeevent);
    }
}
