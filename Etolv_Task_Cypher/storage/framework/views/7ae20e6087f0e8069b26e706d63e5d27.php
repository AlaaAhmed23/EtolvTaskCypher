

<?php $__env->startSection('content'); ?>
<h1>Edit School</h1>
<form action="<?php echo e(route('schools.update', $school['id'])); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>
    <input type="text" name="name" value="<?php echo e($school['name']); ?>" required>
    <button type="submit">Update</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/schools/edit.blade.php ENDPATH**/ ?>