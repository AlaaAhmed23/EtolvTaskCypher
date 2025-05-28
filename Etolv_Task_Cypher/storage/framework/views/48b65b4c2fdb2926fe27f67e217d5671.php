

<?php $__env->startSection('content'); ?>
<h1>School Details</h1>
<p><strong>Name:</strong> <?php echo e($school['name']); ?></p>
<a href="<?php echo e(route('schools.index')); ?>">Back</a>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/schools/show.blade.php ENDPATH**/ ?>