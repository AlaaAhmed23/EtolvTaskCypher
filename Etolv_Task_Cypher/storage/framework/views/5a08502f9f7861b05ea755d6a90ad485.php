
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Edit Student</h2>
        </div>

        <div class="card-body">
            <form action="<?php echo e(route('students.update', $student['id'])); ?>" method="POST" class="needs-validation"
                novalidate>
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <label for="name" class="form-label">Student Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?php echo e(old('name', $student['name'])); ?>" required>
                    <div class="invalid-feedback">Please provide a valid name.</div>
                </div>

                <div class="mb-3">
                    <label for="school_id" class="form-label">School</label>
                    <select class="form-select" id="school_id" name="school_id" required>
                        <?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($school['id']); ?>"
                            <?php echo e(($student['school']['id'] ?? null) == $school['id'] ? 'selected' : ''); ?>>
                            <?php echo e($school['name']); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <div class="invalid-feedback">Please select a school.</div>
                </div>

                <div class="mb-4">
                    <label for="subject_ids" class="form-label">Subjects</label>
                    <select class="form-select" id="subject_ids" name="subject_ids[]" multiple size="5">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($subject['id']); ?>"
                            <?php echo e(in_array($subject['id'], $subjects) ? 'selected' : ''); ?>>
                            <?php echo e($subject['name']); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <small class="text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple</small>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?php echo e(route('students.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 0.5rem;
}

.card-header {
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.form-select[multiple] {
    min-height: 150px;
    padding: 0.5rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}
</style>

<script>
// Bootstrap form validation
(function() {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/students/edit.blade.php ENDPATH**/ ?>