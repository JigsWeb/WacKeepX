<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Checkbox;

class Note extends Model
{
    protected $fillable = ['color', 'title', 'content'];

    static function quickCreate($request){
        $note = new self();

        $note->user_id = Auth::user()->id;
        $note->color = $request->color;
        $note->title = $request->title;
        $note->content = $request->content;

        if($note->save()){
            if(!empty($request->checkboxes_value)){
                foreach($request->checkboxes_value as $key => $value){
                    $checkbox = new Checkbox;

                    $checkbox->note_id = $note->id;
                    $checkbox->content = $value;
                    $checkbox->checked = $request->checkboxes_checked[$key];

                    $checkbox->save();
                }
            }

            return $note;
        }
    }

    /**
     * Get the notes of user.
     */
    public function checkboxes()
    {
        return $this->hasMany('App\Checkbox');
    }
}
