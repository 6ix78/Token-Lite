<?php
/**
 *
 * @package TokenLite
 * @author Softnio
 * @version 1.2.0
 */
namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class TokenCredit extends Model
{
    /*
     * Table Name Specified
     */
    protected $table = 'token_credit';
   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['PaymentId', 'hash','TransactionId','Payment Name','Date','Amount Paid','Token amount','Status'];


}
