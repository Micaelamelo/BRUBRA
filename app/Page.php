<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
  protected $casts = [
      'frequent' => 'array',
      'contacts' => 'array',
      'puntajes' => 'array',
  ];

  protected $fillable = [
     'name',
     'frequent',
     'contacts',
     'puntajes',
     'vendedor',
 ];
}
