<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo e(config('app.name', 'Laravel')); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
    body {
        font-family: Arial, sans-serif;
        margin: 2em;
        background-color: #f9f9f9;
    }

    a {
        margin: 0 5px;
    }

    nav {
        margin-bottom: 20px;
    }

    form {
        display: inline;
    }
    </style>
</head>

<body>

    <nav>
        <a href="<?php echo e(route('schools.index')); ?>">Schools</a> |
        <a href="<?php echo e(route('students.index')); ?>">Students</a> |
        <a href="<?php echo e(route('subjects.index')); ?>">Subjects</a>
    </nav>

    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

</body>

</html><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/layouts/app.blade.php ENDPATH**/ ?>