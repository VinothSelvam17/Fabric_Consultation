<?php namespace App\Models;

use CodeIgniter\Model;

class DcEntryModel extends Model
{
    protected $table = 'dc_entries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'colour_id', 'dc_no', 'roll_number', 'length', 
        'width', 'width_unit', 'shade', 'internal_length', 'internal_width'
    ];
    protected $useTimestamps = true;
    
    public function getDcEntriesForColour($colourId)
    {
        return $this->where('colour_id', $colourId)->findAll();
    }
    
    public function rollNumberExists($rollNumber)
    {
        return $this->where('roll_number', $rollNumber)->countAllResults() > 0;
    }
    
    public function dcNumberExistsForColour($colourId, $dcNo)
    {
        return $this->where('colour_id', $colourId)
                    ->where('dc_no', $dcNo)
                    ->countAllResults() > 0;
    }
}