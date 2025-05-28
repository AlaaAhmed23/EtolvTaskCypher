

<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="mb-4">📋 Student Subject & School Report</h1>

    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="school" class="form-label">Filter by School</label>
            <select name="school" id="school" class="form-select">
                <option value="">All Schools</option>
                <?php $__currentLoopData = $allSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s['name']); ?>" <?php echo e(request('school') == $s['name'] ? 'selected' : ''); ?>>
                    <?php echo e($s['name']); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="subject" class="form-label">Filter by Subject</label>
            <select name="subject" id="subject" class="form-select">
                <option value="">All Subjects</option>
                <?php $__currentLoopData = $allSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($sub['name']); ?>" <?php echo e(request('subject') == $sub['name'] ? 'selected' : ''); ?>>
                    <?php echo e($sub['name']); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="<?php echo e(route('students.report')); ?>" class="btn btn-secondary">Reset</a>
            <a href="<?php echo e(route('students.report', array_merge(request()->all(), ['export' => 'csv']))); ?>"
                class="btn btn-success"> Export CSV </a>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Student Name</th>
                <th>School</th>
                <th>Subjects</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <tr>
                <td><?php echo e($student['name']); ?></td>
                <td><?php echo e($student['school']['name'] ?? 'N/A'); ?></td>
                <td>
                    <?php $__empty_2 = true; $__currentLoopData = $student['subjects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                    <span class="badge bg-primary"><?php echo e($subject['name']); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                    <span class="text-muted">No subjects</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <tr>
                <td colspan="3" class="text-center">No student data available.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/students/report.blade.php ENDPATH**/ ?>