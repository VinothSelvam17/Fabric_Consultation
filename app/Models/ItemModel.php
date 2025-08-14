<?php namespace App\Models;

use CodeIgniter\Model;

class ItemModel extends Model
{
    protected $table = 'items';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_number'];
    protected $useTimestamps = true;
    
    public function getItemByNumber($itemNumber)
    {
        return $this->where('item_number', $itemNumber)->first();
    }
}