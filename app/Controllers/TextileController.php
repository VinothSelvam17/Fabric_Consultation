<?php namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ColourModel;
use App\Models\DcEntryModel;

class TextileController extends BaseController
{
    protected $itemModel;
    protected $colourModel;
    protected $dcEntryModel;
    
    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->colourModel = new ColourModel();
        $this->dcEntryModel = new DcEntryModel();
    }
    
    public function index()
    {
        $data = [
            'title' => 'Fabric',
            'items' => $this->itemModel->findAll()
        ];
        
        return view('textile/index', $data);
    }
    
    // Add a new item
    public function addItem()
    {
        $rules = [
            'item_number' => 'required|is_unique[items.item_number]'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->itemModel->save([
            'item_number' => $this->request->getPost('item_number')
        ]);
        
        return redirect()->to('/textile')->with('message', 'Item added successfully');
    }
    
    // Add a new colour to an item
    public function addColour()
    {
        $rules = [
            'item_id' => 'required|numeric',
            'colour_name' => 'required'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $this->colourModel->save([
            'item_id' => $this->request->getPost('item_id'),
            'colour_name' => $this->request->getPost('colour_name')
        ]);
        
        return redirect()->to('/textile')->with('message', 'Colour added successfully');
    }
    
    // Add a new DC entry
    public function addDcEntry()
    {
        $rules = [
            'colour_id' => 'required|numeric',
            'dc_no' => 'required',
            'roll_number' => 'required|alpha_numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'width_unit' => 'required|in_list[INCH,CM]',
            'shade' => 'required',
            'internal_length' => 'required|numeric',
            'internal_width' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        if ($this->dcEntryModel->rollNumberExists($this->request->getPost('roll_number'))) {
            return redirect()->back()->withInput()->with('error', 'Roll number already exists');
        }
        
        if ($this->dcEntryModel->dcNumberExistsForColour(
            $this->request->getPost('colour_id'),
            $this->request->getPost('dc_no')
        )) {
            return redirect()->back()->withInput()->with('error', 'DC number already exists for this colour');
        }
        
        $this->dcEntryModel->save([
            'colour_id' => $this->request->getPost('colour_id'),
            'dc_no' => $this->request->getPost('dc_no'),
            'roll_number' => $this->request->getPost('roll_number'),
            'length' => $this->request->getPost('length'),
            'width' => $this->request->getPost('width'),
            'width_unit' => $this->request->getPost('width_unit'),
            'shade' => $this->request->getPost('shade'),
            'internal_length' => $this->request->getPost('internal_length'),
            'internal_width' => $this->request->getPost('internal_width')
        ]);
        
        return redirect()->to('/textile')->with('message', 'DC entry added successfully');
    }
    
    public function viewItem($itemId)
    {
        $item = $this->itemModel->find($itemId);
        
        if (empty($item)) {
            return redirect()->to('/textile')->with('error', 'Item not found');
        }
        
        $colours = $this->colourModel->getColoursForItem($itemId);
        $data = [
            'title' => 'View Item: ' . $item['item_number'],
            'item' => $item,
            'colours' => $colours,
            'dcEntries' => []
        ];
        
        foreach ($colours as $colour) {
            $data['dcEntries'][$colour['id']] = $this->dcEntryModel->getDcEntriesForColour($colour['id']);
        }
        
        return view('textile/view_item', $data);
    }
    
public function groupByWidth() 
{
    $range = max(0.1, (float) $this->request->getPost('width_range'));
    
    try {
        $db = \Config\Database::connect();
        
        $query = $db->query("
            SELECT dc_entries.*, colours.colour_name, items.item_number
            FROM dc_entries
            JOIN colours ON dc_entries.colour_id = colours.id
            JOIN items ON colours.item_id = items.id
            ORDER BY width ASC
        ");
        
        $allEntries = $query->getResultArray();
        $groups = [];
        
        if (!empty($allEntries)) {
            $minWidth = (float) $allEntries[0]['width'];
            $maxWidth = $minWidth + $range;
            $groupNumber = 1;
            
            foreach ($allEntries as $entry) {
                $entryWidth = (float) $entry['width'];
                
                if ($entryWidth > $maxWidth) {
                    $minWidth = $entryWidth;
                    $maxWidth = $minWidth + $range;
                    $groupNumber++;
                }
                
                if (!isset($groups['G' . $groupNumber])) {
                    $groups['G' . $groupNumber] = [];
                }
                
                $groups['G' . $groupNumber][] = $entry;
            }
        }
        
        $data = [
            'title' => 'Width Grouping (Range: ' . $range . ')',
            'groups' => $groups,
            'groupType' => 'width'
        ];
        
        return view('textile/grouping_result', $data);
    } catch (\Exception $e) {
        log_message('error', 'Error in groupByWidth: ' . $e->getMessage());
        
        return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
    }
}

    
public function groupByInternal() 
{
    $db = \Config\Database::connect();
    
    $lengthRange = max(0.1, (float) ($this->request->getPost('length_range') ?? 1));
    $widthRange = max(0.1, (float) ($this->request->getPost('width_range') ?? 1));
    
    try {
        $query = $db->query("
            SELECT dc_entries.*, colours.colour_name, items.item_number
            FROM dc_entries
            JOIN colours ON dc_entries.colour_id = colours.id
            JOIN items ON colours.item_id = items.id
            ORDER BY internal_length ASC, internal_width ASC
        ");
        
        $allEntries = $query->getResultArray();
        $groups = [];
        
        foreach ($allEntries as $entry) {
            $lengthGroup = floor((float) $entry['internal_length'] / $lengthRange);
            $widthGroup = floor((float) $entry['internal_width'] / $widthRange);
            
            $groupKey = 'IG_' . $lengthGroup . '_' . $widthGroup;
            
            if (!isset($groups[$groupKey])) {
                $groups[$groupKey] = [];
            }
            
            $groups[$groupKey][] = $entry;
        }
        
        $data = [
            'title' => 'Internal Grouping (Length Range: ' . $lengthRange . ', Width Range: ' . $widthRange . ')',
            'groups' => $groups,
            'groupType' => 'internal'
        ];
        
        return view('textile/grouping_result', $data);
    } catch (\Exception $e) {
        log_message('error', 'Error in groupByInternal: ' . $e->getMessage());
        
        return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
    }
}

public function groupByShade() 
{
    $db = \Config\Database::connect();
    
    $specificShade = $this->request->getPost('shade') ?? '';
    
    try {
        if (!empty($specificShade)) {
            $query = $db->query("
                SELECT dc_entries.*, colours.colour_name, items.item_number
                FROM dc_entries
                JOIN colours ON dc_entries.colour_id = colours.id
                JOIN items ON colours.item_id = items.id
                WHERE dc_entries.shade = ?
                ORDER BY dc_entries.shade ASC
            ", [$specificShade]);
        } else {
            $query = $db->query("
                SELECT dc_entries.*, colours.colour_name, items.item_number
                FROM dc_entries
                JOIN colours ON dc_entries.colour_id = colours.id
                JOIN items ON colours.item_id = items.id
                ORDER BY dc_entries.shade ASC
            ");
        }
        
        $allEntries = $query->getResultArray();
        $groups = [];
        
        foreach ($allEntries as $entry) {
            $shade = $entry['shade'];
            
            if (!isset($groups[$shade])) {
                $groups[$shade] = [];
            }
            
            $groups[$shade][] = $entry;
        }
        
        $data = [
            'title' => 'Shade Grouping' . (!empty($specificShade) ? ' (Shade: ' . $specificShade . ')' : ''),
            'groups' => $groups,
            'groupType' => 'shade'
        ];
        
        return view('textile/grouping_result', $data);
    } catch (\Exception $e) {
        log_message('error', 'Error in groupByShade: ' . $e->getMessage());
        
        return redirect()->back()->with('error', 'An error occurred while processing your request: ' . $e->getMessage());
    }
}


    public function editDcEntry($id)
    {
        $dcEntry = $this->dcEntryModel->find($id);
        
        if (empty($dcEntry)) {
            return redirect()->to('/textile')->with('error', 'DC Entry not found');
        }
        
        $colour = $this->colourModel->find($dcEntry['colour_id']);
        $item = $this->itemModel->find($colour['item_id']);
        
        $data = [
            'title' => 'Edit DC Entry',
            'dcEntry' => $dcEntry,
            'colour' => $colour,
            'item' => $item
        ];
        
        return view('textile/edit_dc_entry', $data);
    }
    
    // Update DC Entry
    public function updateDcEntry($id)
    {
        $rules = [
            'dc_no' => 'required',
            'roll_number' => 'required|alpha_numeric',
            'length' => 'required|numeric',
            'width' => 'required|numeric',
            'width_unit' => 'required|in_list[INCH,CM]',
            'shade' => 'required',
            'internal_length' => 'required|numeric',
            'internal_width' => 'required|numeric'
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $currentEntry = $this->dcEntryModel->find($id);
        $newDcNo = $this->request->getPost('dc_no');
        $newRollNumber = $this->request->getPost('roll_number');
        
        $existingRoll = $this->dcEntryModel->where('roll_number', $newRollNumber)
                                         ->where('id !=', $id)
                                         ->first();
        if ($existingRoll) {
            return redirect()->back()->withInput()->with('error', 'Roll number already exists');
        }
        
        $existingDc = $this->dcEntryModel->where('colour_id', $currentEntry['colour_id'])
                                        ->where('dc_no', $newDcNo)
                                        ->where('id !=', $id)
                                        ->first();
        if ($existingDc) {
            return redirect()->back()->withInput()->with('error', 'DC number already exists for this colour');
        }
        
        $this->dcEntryModel->update($id, [
            'dc_no' => $newDcNo,
            'roll_number' => $newRollNumber,
            'length' => $this->request->getPost('length'),
            'width' => $this->request->getPost('width'),
            'width_unit' => $this->request->getPost('width_unit'),
            'shade' => $this->request->getPost('shade'),
            'internal_length' => $this->request->getPost('internal_length'),
            'internal_width' => $this->request->getPost('internal_width')
        ]);
        
        return redirect()->to('/textile')->with('message', 'DC entry updated successfully');
    }
    
    // Delete DC Entry
    public function deleteDcEntry($id)
    {
        $dcEntry = $this->dcEntryModel->find($id);
        
        if (empty($dcEntry)) {
            return redirect()->to('/textile/viewItem')->with('error', 'DC Entry not found');
        }
        
        $this->dcEntryModel->delete($id);
        
        return redirect()->to('/textile/viewItem')->with('message', 'DC entry deleted successfully');
    }
    
    // Delete Colour
    public function deleteColour($id)
    {
        $colour = $this->colourModel->find($id);
        
        if (empty($colour)) {
            return redirect()->to('/textile/viewItem')->with('error', 'Colour not found');
        }
        
        $this->colourModel->delete($id);
        
        return redirect()->to('/textile/viewItem')->with('message', 'Colour and associated DC entries deleted successfully');
    }
    
    // Delete Item
    public function deleteItem($id)
    {
        $item = $this->itemModel->find($id);
        
        if (empty($item)) {
            return redirect()->to('/')->with('error', 'Item not found');
        }
        
        $this->itemModel->delete($id);
        
        return redirect()->to('/')->with('message', 'Item and all associated data deleted successfully');
    }
}