<?php

/**

 * contact Model

 *

 * Store the contact of user

 *

 * @package TokenLite

 * @author Softnio

 * @version 1.0

 */

namespace App\Models;



use Illuminate\Database\Eloquent\Model;



class Contact extends Model

{

    /*

     * Table Name Specified

     */

    protected $table = 'contacts';



    /**

     * The attributes that are mass assignable.

     *

     * @var array

     */

    protected $fillable = [

        'fname', 'fname', 'email', 'phone','message','myfile',

    ];

}

