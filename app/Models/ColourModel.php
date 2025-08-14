<?php namespace App\Models;

use CodeIgniter\Model;

class ColourModel extends Model
{
    protected $table = 'colours';
    protected $primaryKey = 'id';
    protected $allowedFields = ['item_id', 'colour_name'];
    protected $useTimestamps = true;
    
    public function getColoursForItem($itemId)
    {
        return $this->where('item_id', $itemId)->findAll();
    }
}