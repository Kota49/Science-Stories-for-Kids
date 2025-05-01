<?php

/**
 *
 *@copyright : ToXSL Technologies Pvt. Ltd. < www.toxsl.com >
 *@author     : Shiv Charan Panjeta < shiv@toxsl.com >
 *
 * All Rights Reserved.
 * Proprietary and confidential :  All information contained herein is, and remains
 * the property of ToXSL Technologies Pvt. Ltd. and its partners.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 */
namespace app\components;

/**
 * 
 * Manage Dash boxes 
 *
 */
class TDashBox extends TBaseWidget
{

    public $items = [];

    public function init()
    {
        parent::init();
        foreach ($this->items as &$item) {
            if (! isset($item['color'])) {
                $item['color'] = 'green';
            }
            if (! isset($item['visible'])) {
                $item['visible'] = true;
            }
            if (! isset($item['icon'])) {
                $item['icon'] = 'fa fa-users';
            }
        }
    }

    public function renderHtml()
    {
        ?>

<!--state overview start-->

<div class="row state-overview">
	<?php

        foreach ($this->items as $item) {

            if (! $item['visible'])
                continue;
            ?>
	
		<div class="col-md-4 col-lg-4 col-xl-3 col-sm-6">
		
		<?php // Dont make <a> tag if url not set
		if (isset($item['url'])) {  ?>
                <a href="<?php echo $item['url']?>">
                   <?php }?>
                   
			<section class="cardbox <?php echo $item['color']?>">
				<div class="symbol">
					<i class="<?php echo $item['icon']?>"></i>
				</div>
				<div class="value white">
					<h3 data-speed="1000" data-to="320" data-from="0" class="timer"><?php echo $item['data']?></h3>
					<p><?php echo $item['header']?></p>
				</div>
			</section>
		</a>
	</div>


<?php }?>
</div>

<?php
    }
}
