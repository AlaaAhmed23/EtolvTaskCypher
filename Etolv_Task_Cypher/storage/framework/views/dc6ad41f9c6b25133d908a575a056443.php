

<?php $__env->startSection('content'); ?>
<h1>Schools</h1>
<a href="<?php echo e(route('schools.create')); ?>">Create New School</a>
<ul>
    <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li>
        <?php echo e($school['name']); ?> |
        <a href="<?php echo e(route('schools.show', $school['id'])); ?>">View</a> |
        <a href="<?php echo e(route('schools.edit', $school['id'])); ?>">Edit</a>
        <form action="<?php echo e(route('schools.destroy', $school['id'])); ?>" method="POST" style="display:inline;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit">Delete</button>
        </form>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/schools/index.blade.php ENDPATH**/ ?>