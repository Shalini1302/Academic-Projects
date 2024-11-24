<?php if (isset($success_message)): ?>
    <div class="success-message" style="color: green;">
        <?php echo $success_message; ?>
    </div>
<?php elseif (isset($error)): ?>
    <div class="error-message" style="color: red;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>