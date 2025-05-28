
<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>Students (Paginated)</h1>

    <form method="GET" action="<?php echo e(route('students.paginated')); ?>">
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control" placeholder="Search students..."
                value="<?php echo e(request('search')); ?>">
            <button class="btn btn-outline-secondary" type="submit">Search</button>
        </div>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>School</th>
                <th>Subjects</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($student['name']); ?></td>
                <td><?php echo e($student['school']['name'] ?? 'N/A'); ?></td>
                <td>
                    <?php $__currentLoopData = $student['subjects']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-primary"><?php echo e($subject['name']); ?></span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </td>
                <td>
                    <a href="<?php echo e(route('students.show', $student['id'])); ?>" class="btn btn-sm btn-info">View</a>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php
            $totalPages = ceil($pagination['total'] / $pagination['per_page']);
            $currentPage = $pagination['current_page'];
            ?>

            
            <?php if($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo e(request()->fullUrlWithQuery(['page' => $currentPage - 1])); ?>"
                    aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
            <?php endif; ?>

            
            <?php for($i = 1; $i <= $totalPages; $i++): ?> <li class="page-item <?php echo e($i == $currentPage ? 'active' : ''); ?>">
                <a class="page-link" href="<?php echo e(request()->fullUrlWithQuery(['page' => $i])); ?>"><?php echo e($i); ?></a>
                </li>
                <?php endfor; ?>

                
                <?php if($currentPage < $totalPages): ?> <li class="page-item">
                    <a class="page-link" href="<?php echo e(request()->fullUrlWithQuery(['page' => $currentPage + 1])); ?>"
                        aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                    </li>
                    <?php endif; ?>
        </ul>
    </nav>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\etolv_task_neo4j\resources\views/students/paginated-index.blade.php ENDPATH**/ ?>