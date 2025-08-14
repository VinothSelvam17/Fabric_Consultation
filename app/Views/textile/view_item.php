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
        
        <div class="card">
            <div class="card-header">
                <h3>Item Details</h3>
            </div>
            <div class="card-body">
                <p><strong>Item Number:</strong> <?= $item['item_number'] ?></p>
            </div>
        </div>
        
        <?php foreach($colours as $colour): ?>
            <div class="card">
                <div class="card-header">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <h4>Colour: <?= $colour['colour_name'] ?></h4>
                        <a href="<?= site_url('textile/deleteColour/'.$colour['id']) ?>" class="btn small danger" onclick="return confirm('Are you sure you want to delete this colour and all associated DC entries?')">Delete Colour</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(isset($dcEntries[$colour['id']]) && !empty($dcEntries[$colour['id']])): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>DC.NO</th>
                                    <th>Roll Number</th>
                                    <th>Length</th>
                                    <th>Width</th>
                                    <th>Unit</th>
                                    <th>Shade</th>
                                    <th>Internal Length</th>
                                    <th>Internal Width</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($dcEntries[$colour['id']] as $entry): ?>
                                    <tr>
                                        <td><?= $entry['dc_no'] ?></td>
                                        <td><?= $entry['roll_number'] ?></td>
                                        <td><?= $entry['length'] ?></td>
                                        <td><?= $entry['width'] ?></td>
                                        <td><?= $entry['width_unit'] ?></td>
                                        <td><?= $entry['shade'] ?></td>
                                        <td><?= $entry['internal_length'] ?></td>
                                        <td><?= $entry['internal_width'] ?></td>
                                        <td>
                                            <a href="<?= site_url('textile/editDcEntry/'.$entry['id']) ?>" class="btn small">Edit</a>
                                            <a href="<?= site_url('textile/deleteDcEntry/'.$entry['id']) ?>" class="btn small danger" onclick="return confirm('Are you sure you want to delete this DC entry?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>No DC entries found for this colour.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>