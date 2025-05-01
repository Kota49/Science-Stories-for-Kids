<?php
use app\components\TDashboard;

?>
<div class="setting-form">
    <div class="wrapper">

        <?php
        echo TDashboard::widget([
            'items' => $items
        ]);
        ?>
    </div>
</div>