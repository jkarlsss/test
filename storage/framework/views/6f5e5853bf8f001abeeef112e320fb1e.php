<?php

use App\Models\User;
use Livewire\Volt\Component;

?>

<div>

    <?php if (isset($component)) { $__componentOriginal5f29597eb84bf278316da8433bdb9a84 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5f29597eb84bf278316da8433bdb9a84 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.manage.layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('manage.layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
      <table class="w-full p-2 text-center border rounded-lg">
        <thead>
          <tr class="border">
            <th class="">ID</th>
            <th class="">Name</th>
            <th class="">Email</th>
            <th class="">Actions</th>
          </tr>
        </thead>
        <tbody class="border">
          <tr>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <td class="border"><?php echo e($user->id); ?></td>
              <td class="border"><?php echo e($user->name); ?></td>
              <td class="border"><?php echo e($user->email); ?></td>
              <td class="border">
              </td>
              
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            
          </tr>
        </tbody>
      </table>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5f29597eb84bf278316da8433bdb9a84)): ?>
<?php $attributes = $__attributesOriginal5f29597eb84bf278316da8433bdb9a84; ?>
<?php unset($__attributesOriginal5f29597eb84bf278316da8433bdb9a84); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5f29597eb84bf278316da8433bdb9a84)): ?>
<?php $component = $__componentOriginal5f29597eb84bf278316da8433bdb9a84; ?>
<?php unset($__componentOriginal5f29597eb84bf278316da8433bdb9a84); ?>
<?php endif; ?>
</div><?php /**PATH C:\Users\jkarls\Herd\test_web_new\resources\views\livewire/manage.blade.php ENDPATH**/ ?>