<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="container">
        <h1><?= $title ?></h1>
        
        <a href="<?= site_url('textile') ?>" class="btn">Back to Home</a>
        
        <?php if(session()->getFlashdata('error')): ?>
            <div class="error-message">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h3>Edit DC Entry</h3>
            </div>
            <div class="card-body">
                <p><strong>Item:</strong> <?= $item['item_number'] ?></p>
                <p><strong>Colour:</strong> <?= $colour['colour_name'] ?></p>
                
                <form action="<?= site_url('textile/updateDcEntry/'.$dcEntry['id']) ?>" method="post">
                    <div class="form-group">
                        <label for="dc_no">DC.NO</label>
                        <input type="text" id="dc_no" name="dc_no" value="<?= $dcEntry['dc_no'] ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="roll_number">Roll Number</label>
                        <input type="text" id="roll_number" name="roll_number" value="<?= $dcEntry['roll_number'] ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="length">Length</label>
                            <input type="number" step="0.01" id="length" name="length" value="<?= $dcEntry['length'] ?>" required>
                        </div>
                        
                        <div class="form-group half">
                            <label for="width">Width</label>
                            <input type="number" step="0.01" id="width" name="width" value="<?= $dcEntry['width'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="width_unit">Width Unit</label>
                        <select id="width_unit" name="width_unit" required>
                            <option value="INCH" <?= $dcEntry['width_unit'] == 'INCH' ? 'selected' : '' ?>>INCH</option>
                            <option value="CM" <?= $dcEntry['width_unit'] == 'CM' ? 'selected' : '' ?>>CM</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="shade">Shade</label>
                        <input type="text" id="shade" name="shade" value="<?= $dcEntry['shade'] ?>" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group half">
                            <label for="internal_length">Internal Length</label>
                            <input type="number" step="0.01" id="internal_length" name="internal_length" value="<?= $dcEntry['internal_length'] ?>" required>
                        </div>
                        
                        <div class="form-group half">
                            <label for="internal_width">Internal Width</label>
                            <input type="number" step="0.01" id="internal_width" name="internal_width" value="<?= $dcEntry['internal_width'] ?>" required>
                        </div>
                    </div>
                    
                    <div class="form-buttons">
                        <button type="submit" class="btn primary">Update DC Entry</button>
                        <a href="<?= site_url('textile') ?>" class="btn">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>