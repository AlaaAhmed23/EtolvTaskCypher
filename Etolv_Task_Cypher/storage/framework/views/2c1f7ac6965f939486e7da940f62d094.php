

<?php $__env->startSection('content'); ?>
<h1>Subjects</h1>
<a href="<?php echo e(route('subjects.create')); ?>">Create New Subject</a>
<ul>
    <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <li>
        <?php echo e($subject['name']); ?> |
        <a href="<?php echo e(route('subjects.show', $subject['id'])); ?>">View</a> |
        <a href="<?php echo e(route('subjects.edit', $subject['id'])); ?>">Edit</a>
        <form action="<?php echo e(route('subjects.destroy', $subject['id'])); ?>" method="POST" style="display:inline;">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <button type="submit">Delete</button>
        </form>
    </li>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</ul>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/subjects/index.blade.php ENDPATH**/ ?>