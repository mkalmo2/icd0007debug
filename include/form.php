<form>

<?php foreach (range(1, 5) as $grade): ?>

    <label>
    <input type="radio"
           name="grade"
           <?= $grade === $selectedGrade ? 'checked' : ''; ?>
           value="<?= $grade ?>" />
    <?= $grade ?>
    </label>

<?php endforeach; ?>

</form>
