
<?php $__env->startSection('content'); ?>
<h1>Create Student</h1>

<form action="<?php echo e(route('students.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <label>Name</label>
    <input type="text" name="name" required>

    <label>School</label>
    <select name="school_id" required>
        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($school['id']); ?>"><?php echo e($school['name']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <label>Subjects</label>
    <select name="subject_ids[]" multiple>
        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($subject['id']); ?>"><?php echo e($subject['name']); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>

    <button type="submit">Create</button>
</form>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/students/create.blade.php ENDPATH**/ ?>