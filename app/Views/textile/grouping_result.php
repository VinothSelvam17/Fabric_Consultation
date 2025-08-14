<!-- app/Views/textile/grouping_result.php -->
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
        
        <?php if(empty($groups)): ?>
            <div class="error-message">No data found for grouping.</div>
        <?php else: ?>
            <?php foreach($groups as $groupName => $groupItems): ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Group: <?= $groupName ?></h4>
                    </div>
                    <div class="card-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Item Number</th>
                                    <th>Colour</th>
                                    <th>Roll Number</th>
                                    <th>Length</th>
                                    <th>Width</th>
                                    <th>Unit</th>
                                    <th>Shade</th>
                                    <th>Internal Length</th>
                                    <th>Internal Width</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($groupItems as $item): ?>
                                    <tr>
                                        <td><?= $item['item_number'] ?></td>
                                        <td><?= $item['colour_name'] ?></td>
                                        <td><?= $item['roll_number'] ?></td>
                                        <td><?= $item['length'] ?></td>
                                        <td><?= $item['width'] ?></td>
                                        <td><?= $item['width_unit'] ?></td>
                                        <td><?= $item['shade'] ?></td>
                                        <td><?= $item['internal_length'] ?></td>
                                        <td><?= $item['internal_width'] ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>