<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="NovaCart Recruitment Website">
    <meta name="keywords" content="NovaCart, Recruitment">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= $pageTitle ?></title>

    <link rel="stylesheet" href="styles/styles.css">
    <?php if (!empty($pageStylesheet)): ?>
    <link rel="stylesheet" href="<?= htmlspecialchars($pageStylesheet, ENT_QUOTES, 'UTF-8') ?>">
    <?php endif; ?>
</head>

