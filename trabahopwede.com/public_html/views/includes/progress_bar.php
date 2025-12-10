<?php
function renderProgressBar($currentStep, $steps) {
    echo '<div class="container mb-4">'; // Add Bootstrap container
    echo '<div class="custom-progress-bar d-flex justify-content-between w-100">';
    foreach ($steps as $i => $stepLabel) {
        $stepClass = ($i + 1) == $currentStep ? 'step active' : 'step inactive';
        echo "<div class=\"$stepClass\">Step " . ($i + 1) . "<br><small>$stepLabel</small></div>";
    }
    echo '</div></div>';
}
?>