<!-- app/Views/textile/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        
        <?php if(session()->getFlashdata('message')): ?>
            <div class="success-message">
                <?= session()->getFlashdata('message') ?>
            </div>
        <?php endif; ?>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="error-message">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col">
                <div class="card">
                    <div class="card-header">Add New Item</div>
                    <div class="card-body">
                        <form action="<?= site_url('textile/addItem') ?>" method="post">
                            <div class="form-group">
                                <label for="item_number">Item Number</label>
                                <input type="text" id="item_number" name="item_number" required>
                            </div>
                            <button type="submit" class="btn primary">Add Item</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card">
                    <div class="card-header">Add Colour to Item</div>
                    <div class="card-body">
                        <form action="<?= site_url('textile/addColour') ?>" method="post">
                            <div class="form-group">
                                <label for="item_id">Select Item</label>
                                <select id="item_id" name="item_id" required>
                                    <option value="">-- Select Item --</option>
                                    <?php foreach($items as $item): ?>
                                        <option value="<?= $item['id'] ?>"><?= $item['item_number'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="colour_name">Colour Name</label>
                                <input type="text" id="colour_name" name="colour_name" required>
                            </div>
                            <button type="submit" class="btn primary">Add Colour</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="card">
                    <div class="card-header">Add DC Entry</div>
                    <div class="card-body">
                        <form action="<?= site_url('textile/addDcEntry') ?>" method="post">
                            <div class="form-group">
                                <label for="colour_id">Select Colour</label>
                                <select id="colour_id" name="colour_id" required>
                                    <option value="">-- Select Colour --</option>
                                    <?php foreach($items as $item): ?>
                                        <?php $colours = (new \App\Models\ColourModel())->getColoursForItem($item['id']); ?>
                                        <?php foreach($colours as $colour): ?>
                                            <option value="<?= $colour['id'] ?>"><?= $item['item_number'] ?> - <?= $colour['colour_name'] ?></option>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dc_no">DC.NO</label>
                                <input type="text" id="dc_no" name="dc_no" required>
                            </div>
                            <div class="form-group">
                                <label for="roll_number">Roll Number</label>
                                <input type="text" id="roll_number" name="roll_number" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="length">Length</label>
                                    <input type="number" step="0.01" id="length" name="length" required>
                                </div>
                                <div class="form-group half">
                                    <label for="width">Width</label>
                                    <input type="number" step="0.01" id="width" name="width" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="width_unit">Width Unit</label>
                                <select id="width_unit" name="width_unit" required>
                                    <option value="INCH">INCH</option>
                                    <option value="CM">CM</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="shade">Shade</label>
                                <input type="text" id="shade" name="shade" required>
                            </div>
                            <div class="form-row">
                                <div class="form-group half">
                                    <label for="internal_length">Internal Length</label>
                                    <input type="number" step="0.01" id="internal_length" name="internal_length" required>
                                </div>
                                <div class="form-group half">
                                    <label for="internal_width">Internal Width</label>
                                    <input type="number" step="0.01" id="internal_width" name="internal_width" required>
                                </div>
                            </div>
                            <button type="submit" class="btn primary">Add DC Entry</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Data Grouping</div>
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Width Grouping</div>
                            <div class="card-body">
                                <form action="<?= site_url('textile/groupByWidth') ?>" method="post">
                                    <div class="form-group">
                                        <label for="width_range">Width Range</label>
                                        <input type="number" step="0.01" id="width_range" name="width_range" value="1" required>
                                    </div>
                                    <button type="submit" class="btn primary">Group by Width</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Internal Grouping</div>
                            <div class="card-body">
                                <form action="<?= site_url('textile/groupByInternal') ?>" method="post">
                                    <div class="form-group">
                                        <label for="length_range">Length Range</label>
                                        <input type="number" step="0.01" id="length_range" name="length_range" value="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="width_range">Width Range</label>
                                        <input type="number" step="0.01" id="width_range" name="width_range" value="1" required>
                                    </div>
                                    <button type="submit" class="btn primary">Group by Internal</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col">
                        <div class="card">
                            <div class="card-header">Shade Grouping</div>
                            <div class="card-body">
                                <form action="<?= site_url('textile/groupByShade') ?>" method="post">
                                    <div class="form-group">
                                        <label for="shade">Specific Shade (optional)</label>
                                        <input type="text" id="shade" name="shade">
                                    </div>
                                    <button type="submit" class="btn primary">Group by Shade</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">Item List</div>
            <div class="card-body">
                <table>
                    <thead>
                        <tr>
                            <th>Item Number</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($items as $item): ?>
                            <tr>
                                <td><?= $item['item_number'] ?></td>
                                <td>
                                    <a href="<?= site_url('textile/viewItem/'.$item['id']) ?>" class="btn small">View Details</a>
                                    <a href="<?= site_url('textile/deleteItem/'.$item['id']) ?>" class="btn small danger" onclick="return confirm('Are you sure you want to delete this item and all associated data?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>