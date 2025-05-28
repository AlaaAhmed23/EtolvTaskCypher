

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">Students Management</h1>

    <div class="mb-4">
        <a href="<?php echo e(route('students.create')); ?>" class="btn btn-primary">Create New Student</a>
        <a href="<?php echo e(route('students.paginated')); ?>" class="btn btn-info">Test Paginated</a>
        <a href="<?php echo e(route('students.report')); ?>" class="btn btn-success">Students Report</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Student Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($index + 1); ?></td>
                            <td><?php echo e($student['name']); ?></td>
                            <td>
                                <a href="<?php echo e(route('students.show', $student['id'])); ?>"
                                    class="btn btn-sm btn-info">View</a>
                                <a href="<?php echo e(route('students.edit', $student['id'])); ?>"
                                    class="btn btn-sm btn-warning">Edit</a>
                                <form action="<?php echo e(route('students.destroy', $student['id'])); ?>" method="POST"
                                    class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        Showing <?php echo e(count($students)); ?> of <?php echo e(count($students)); ?> students
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/students/index.blade.php ENDPATH**/ ?>