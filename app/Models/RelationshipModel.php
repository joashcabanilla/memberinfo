<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelationshipModel extends Model
{
    use HasFactory;
    protected $table = 'relationships';
    protected $primaryKey = 'id';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    protected $fillable = [
        'relationship',
    ]; 

    function relationshipList(){
        $relationships = $this->get();
        $result = array();

        foreach($relationships as $relationship){
            $result[$relationship->id] = $relationship->id." - ".$relationship->relationship;
        }

        return $result;
    }

    function relationshipListTable(){
        $relationships = $this->get();
        $result = array();

        foreach($relationships as $relationship){
            $result[$relationship->id] = $relationship->relationship;
        }

        return $result;
    }
}
