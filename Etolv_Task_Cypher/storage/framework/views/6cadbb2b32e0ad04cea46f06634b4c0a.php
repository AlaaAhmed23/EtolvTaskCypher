

<?php $__env->startSection('content'); ?>
<h1>Create Subject</h1>
<form action="<?php echo e(route('subjects.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <input type="text" name="name" placeholder="Subject Name" required>
    <button type="submit">Create</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/subjects/create.blade.php ENDPATH**/ ?>