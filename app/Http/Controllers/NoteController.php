<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Note;
use Illuminate\Support\Facades\Input;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $note = Note::quickCreate($request);
        $note = Note::find($note->id);

        $data = $note->toArray();
        $data['checkboxes'] = $note->checkboxes;

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Note $note
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Note $note)
    {
        $data = Input::only('color', 'title', 'content');

        if(!$data['color']) unset($data['color']);
        if(!$data['title']) unset($data['title']);
        if(!$data['content']) unset($data['content']);

        $note->update($data);

        if(!empty($request->checkboxes_value)){
            foreach($request->checkboxes_value as $key => $val){
                if(!empty($request->checkboxes_id[$key])){
                    \App\Checkbox::find($request->checkboxes_id[$key])->update(array(
                        'content' => $val,
                        'checked' => $request->checkboxes_checked[$key]
                    ));
                }
                else{
                    $checkbox = new \App\Checkbox;

                    $checkbox->note_id = $note->id;
                    $checkbox->content = $val;
                    $checkbox->checked = $request->checkboxes_checked[$key];

                    $checkbox->save();
                }
            }
        }

        if(!empty($request->checkboxes_destroy)){
            foreach($request->checkboxes_destroy as $id){
                \App\Checkbox::find($id)->delete();
            }
        }

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Note $note
     * @return \Illuminate\Http\Response
     */
    public function destroy(Note $note)
    {
        $note->delete();

        return response()->json();
    }
}
